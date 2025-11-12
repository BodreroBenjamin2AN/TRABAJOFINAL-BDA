<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Drilldown Ejemplo</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f5f7f8;
            font-family: 'Lexend', Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 1rem 0;
            font-size: 1.1rem;
        }
        a {
            color: #0b73da;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        a:hover {
            color: #1877f2;
            text-decoration: underline;
        }
        .chart-container {
            width: 100%;
            margin-bottom: 2rem;
        }
        .back {
            display: inline-block;
            margin-bottom: 1rem;
            color: #555;
            background: #f0f4fa;
            border-radius: 6px;
            padding: 0.4rem 1rem;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .back:hover {
            background: #e0e7ef;
        }
    </style>
</head>
<body>
<div class="container">
<?php
$regiones = [
    'Santa Fe' => [
        'Sur' => [
            'productos' => ['Botines', 'Gorra'],
            'clientes' => ['María', 'José'],
            'ventas' => 60,
            'costo' => 80,
        ],
    ],
];

// Nivel 1: Mostrar regiones con gráfico
if (!isset($_GET['region'])) {
    // Mostrar solo la región Santa Fe y semaforizar por % vendido sobre stock total de productos
    require_once __DIR__ . '/db.php';
    $stock_inicial_total = 0;
    $stock_actual_total = 0;
    try {
        $sqlTotales = "SELECT SUM(stock_inicial) AS ini, SUM(stock) AS act FROM Producto";
        $resTot = $conn->query($sqlTotales);
        if ($resTot) {
            $agg = $resTot->fetch_assoc();
            $stock_inicial_total = (int)($agg['ini'] ?? 0);
            $stock_actual_total  = (int)($agg['act'] ?? 0);
        }
    } catch (Exception $e) {}

    $vendido_total = max(0, $stock_inicial_total - $stock_actual_total);
    $porcentaje_vendido = $stock_inicial_total > 0 ? round(($vendido_total / $stock_inicial_total) * 100, 2) : 0;

    // Semaforización por % vendido del stock total (verde ≥60, amarillo 30-59, rojo 0-29)
    $color = '#ef4444';
    if ($porcentaje_vendido >= 60) {
        $color = '#22c55e';
    } elseif ($porcentaje_vendido >= 30) {
        $color = '#eab308';
    }

    echo '<h2>Ventas por Región</h2>';
    echo '<a class="back" href="http://localhost/TRABAJOFINAL-BDA/stitch_p_gina_de_inicio/p%C3%A1gina_de_inicio/code.php">← Volver Al Menú</a>';
    echo '<div class="chart-container"><canvas id="regionChart"></canvas></div>';
    echo '<ul>';
    echo '<li><a href="?region=' . urlencode('Santa Fe') . '">Santa Fe</a> <span style="font-size:0.95rem;color:#555">(' . $porcentaje_vendido . '% vendido del stock total)</span></li>';
    echo '</ul>';
    echo '<script>
        const ctx = document.getElementById("regionChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Santa Fe"],
                datasets: [{
                    label: "% de stock vendido",
                    data: [' . json_encode([$porcentaje_vendido]) . '],
                    backgroundColor: [' . json_encode($color) . '],
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "% de stock vendido (semaforizado)" }
                },
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    </script>';
    exit;
}

// Nivel 2: Mostrar sucursales de la región
$region = $_GET['region'];
if (isset($regiones[$region]) && !isset($_GET['sucursal'])) {
    echo '<a class="back" href="drilldown_ejemplo.php">← Volver a regiones</a>';
    echo '<h2>Sucursales en ' . htmlspecialchars($region) . '</h2><ul>';
    foreach ($regiones[$region] as $sucursal => $detalle) {
        echo '<li><a href="?region=' . urlencode($region) . '&sucursal=' . urlencode($sucursal) . '">' . htmlspecialchars($sucursal) . ' (Ventas: ' . $detalle['ventas'] . ')</a></li>';
    }
    echo '</ul>';
    exit;
}

// Nivel 3: Mostrar detalle de sucursal
$sucursal = $_GET['sucursal'] ?? null;
$producto = $_GET['producto'] ?? null;
if ($sucursal && isset($regiones[$region][$sucursal])) {
    $detalle = $regiones[$region][$sucursal];
    // Si se seleccionó un producto, mostrar clientes reales y cantidades desde la BD
    if ($producto) {
        require_once __DIR__ . '/db.php';
        
        // Consultar usuarios reales que compraron este producto
        // usuario_id en Venta vincula directamente con Usuario
        try {
            $sql = "SELECT u.email AS cliente, SUM(v.cantidad) AS cantidad_comprada, SUM(v.total) AS total_gastado
                    FROM Venta v
                    JOIN Producto p ON p.id = v.producto_id
                    JOIN Usuario u ON u.id = v.usuario_id
                    WHERE p.nombre = ?
                    GROUP BY u.id, u.email
                    ORDER BY cantidad_comprada DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $producto);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $clientes_rows = $resultado->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
            $clientes_rows = [];
        }
        
        echo '<a class="back" href="?region=' . urlencode($region) . '&sucursal=' . urlencode($sucursal) . '">← Volver a productos</a>';
        echo '<h2>Clientes que adquirieron ' . htmlspecialchars($producto) . '</h2>';
        
        if (!empty($clientes_rows)) {
            $labels_cli = [];
            $cantidades_cli = [];
            $colores_cli = [];
            
            $n = count($clientes_rows);
            for ($i = 0; $i < $n; $i++) {
                $cliente_email = $clientes_rows[$i]['cliente'] ?? 'Sin usuario';
                $labels_cli[] = $cliente_email;
                
                $cantidad_comp = (int)($clientes_rows[$i]['cantidad_comprada'] ?? 0);
                $cantidades_cli[] = $cantidad_comp;
                
                // Semaforización por cantidad comprada
                // Verde: 20 o más | Amarillo: 10 a 19 | Rojo: 1 a 9 | (0: gris, caso no esperado)
                if ($cantidad_comp >= 20) {
                    $colores_cli[] = '#22c55e'; // verde
                } elseif ($cantidad_comp >= 10) {
                    $colores_cli[] = '#eab308'; // amarillo
                } elseif ($cantidad_comp >= 1) {
                    $colores_cli[] = '#ef4444'; // rojo
                } else {
                    $colores_cli[] = '#9ca3af'; // gris para 0 (no debería aparecer)
                }
            }
            
            // Gráfico de barras con semaforización por ranking
            echo '<div class="chart-container"><canvas id="cliProdChart"></canvas></div>';
            echo '<script>
                const ctxCliProd = document.getElementById("cliProdChart").getContext("2d");
                new Chart(ctxCliProd, {
                    type: "bar",
                    data: {
                        labels: ' . json_encode($labels_cli) . ',
                        datasets: [{
                            label: "Cantidad Comprada",
                            data: ' . json_encode($cantidades_cli) . ',
                            backgroundColor: ' . json_encode($colores_cli) . ',
                        }]
                    },
                    options: {
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: "Cantidad comprada por cliente (ranking)" }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            </script>';
            
            // Tabla
            echo '<table style="width:100%;border-collapse:collapse;margin-bottom:2rem;">';
            echo '<tr style="background:#f5f7f8;"><th style="text-align:left;padding:8px;">Cliente</th><th style="text-align:right;padding:8px;">Cantidad</th><th style="text-align:right;padding:8px;">Total Gastado</th></tr>';
            for ($i = 0; $i < $n; $i++) {
                $cliente_email = $clientes_rows[$i]['cliente'] ?? 'Sin usuario';
                $cantidad = (int)$clientes_rows[$i]['cantidad_comprada'];
                $total = (float)$clientes_rows[$i]['total_gastado'];
                echo '<tr><td style="padding:8px;">' . htmlspecialchars($cliente_email) . '</td><td style="text-align:right;padding:8px;color:' . $colores_cli[$i] . ';font-weight:bold;">' . $cantidad . '</td><td style="text-align:right;padding:8px;">$' . number_format($total, 2) . '</td></tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No hay compras registradas para este producto.</p>';
        }
        exit;
    }
    // Mostrar datos reales SOLO para la URL ?region=Santa+Fe&sucursal=Sur
    if ($region === 'Santa Fe' && $sucursal === 'Sur') {
        require_once __DIR__ . '/db.php';
        try {
            $sql = "SELECT p.nombre AS producto, 
                           SUM(v.cantidad) AS cantidad_vendida, 
                           SUM(v.total) AS total,
                           p.stock_inicial,
                           p.stock AS stock_actual
                    FROM Venta v
                    JOIN Producto p ON p.id = v.producto_id
                    GROUP BY p.id, p.nombre, p.stock_inicial, p.stock
                    ORDER BY cantidad_vendida DESC";
            $res = $conn->query($sql);
            $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Exception $e) {
            $rows = [];
        }
        if (!empty($rows)) {
            $labels = [];
            $cant = [];
            $colores_reales = [];
            
            foreach ($rows as $r) {
                $labels[] = $r['producto'];
                $cant[] = (int)$r['cantidad_vendida'];
                
                // Calcular % vendido: (cantidad_vendida / stock_inicial) * 100
                $stock_inicial = (int)$r['stock_inicial'];
                $cantidad_vendida = (int)$r['cantidad_vendida'];
                $porcentaje = $stock_inicial > 0 ? round(($cantidad_vendida / $stock_inicial) * 100, 2) : 0;
                
                // Semaforización por % de stock vendido
                if ($porcentaje >= 60) {
                    $colores_reales[] = '#22c55e'; // verde: vendió ≥60% del stock inicial
                } elseif ($porcentaje >= 30) {
                    $colores_reales[] = '#eab308'; // amarillo: vendió 30-59% del stock inicial
                } else {
                    $colores_reales[] = '#ef4444'; // rojo: vendió 0-29% del stock inicial
                }
            }
            
            echo '<h2>Ventas por producto (datos reales)</h2>';
            echo '<div class="chart-container"><canvas id="ventasProd"></canvas></div>';
            echo '<ul>';
            foreach ($rows as $r) {
                $stock_inicial = (int)$r['stock_inicial'];
                $cantidad_vendida = (int)$r['cantidad_vendida'];
                $stock_actual = (int)$r['stock_actual'];
                $porcentaje = $stock_inicial > 0 ? round(($cantidad_vendida / $stock_inicial) * 100, 2) : 0;
                
                echo '<li><a href="?region=' . urlencode($region) . '&sucursal=' . urlencode($sucursal) . '&producto=' . urlencode($r['producto']) . '">' . htmlspecialchars($r['producto']) . '</a> — Vendidos: <strong>' . $cantidad_vendida . '</strong> / Stock inicial: <strong>' . $stock_inicial . '</strong> — Total: <strong>$' . number_format((float)$r['total'], 2) . '</strong> <span style="font-size:0.95rem;color:#555">(' . $porcentaje . '% vendido)</span></li>';
            }
            echo '</ul>';
            echo '<script>
                const ctxVP = document.getElementById("ventasProd").getContext("2d");
                new Chart(ctxVP, {
                    type: "bar",
                    data: {
                        labels: ' . json_encode($labels) . ',
                        datasets: [{
                            label: "Cantidad vendida",
                            data: ' . json_encode($cant) . ',
                            backgroundColor: ' . json_encode($colores_reales) . '
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {display:false},
                            title: {display: true, text: "Ventas reales (semaforizado por % de stock vendido)"}
                        },
                        scales: {y: {beginAtZero:true}}
                    }
                });
            </script>';
        }
    }
    echo '<a class="back" href="?region=' . urlencode($region) . '">← Volver a sucursales</a>';
    exit;
}

// Si no existe la región o sucursal
http_response_code(404);
echo '<h2>No encontrado</h2>';
?>
</div>
</body>
</html>
