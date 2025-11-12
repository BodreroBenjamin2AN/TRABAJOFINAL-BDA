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
    'Rosario' => [
        'Centro' => [
            'productos' => ['Pelota', 'Zapatillas', 'Remera'],
            'clientes' => ['Juan', 'Ana', 'Carlos'],
            'ventas' => 120, // Ganancia alta
            'costo' => 60,   // Costo bajo
        ],
        'Norte' => [
            'productos' => ['Short', 'Medias'],
            'clientes' => ['Lucía', 'Pedro'],
            'ventas' => 80, // Ganancia alta
            'costo' => 40,  // Costo bajo
        ],
    ],
    'Santa Fe' => [
        'Sur' => [
            'productos' => ['Botines', 'Gorra'],
            'clientes' => ['María', 'José'],
            'ventas' => 60, // Ganancia negativa
            'costo' => 80,  // Costo alto
        ],
    ],
];

// Nivel 1: Mostrar regiones con gráfico
if (!isset($_GET['region'])) {
    // Sumar ventas y calcular porcentaje de ganancia ficticio por región
    $ventas_por_region = [];
    $ganancia_por_region = [];
    foreach ($regiones as $region => $sucursales) {
        $ventas = 0;
        $costo = 0;
        foreach ($sucursales as $detalle) {
            $ventas += $detalle['ventas'];
            $costo += $detalle['costo'];
        }
        $ventas_por_region[$region] = $ventas;
        // Ganancia ficticia: ventas - costo
        $ganancia = $ventas - $costo;
        // Porcentaje de ganancia sobre ventas
        $porcentaje = $ventas > 0 ? round(($ganancia / $ventas) * 100, 2) : 0;
        $ganancia_por_region[$region] = $porcentaje;
    }
    // Semaforización
    $colores = [];
    foreach ($ganancia_por_region as $porcentaje) {
        if ($porcentaje > 40) {
            $colores[] = "#22c55e"; // verde
        } elseif ($porcentaje >= 0) {
            $colores[] = "#eab308"; // amarillo
        } else {
            $colores[] = "#ef4444"; // rojo
        }
    }
    echo '<h2>Ventas por Región</h2>';
    echo '<a class="back" href="http://localhost/TRABAJOFINAL-BDA/stitch_p_gina_de_inicio/p%C3%A1gina_de_inicio/code.php">← Volver Al Menú</a>';
    echo '<div class="chart-container"><canvas id="regionChart"></canvas></div>';
    echo '<ul>';
    foreach ($regiones as $region => $sucursales) {
        echo '<li><a href="?region=' . urlencode($region) . '">' . htmlspecialchars($region) . '</a> ';
        echo '<span style="font-size:0.95rem;color:#555">(' . $ganancia_por_region[$region] . '%)</span></li>';
    }
    echo '</ul>';
    echo '<script>
        const ctx = document.getElementById("regionChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ' . json_encode(array_keys($ventas_por_region)) . ',
                datasets: [{
                    label: "Ventas",
                    data: ' . json_encode(array_values($ventas_por_region)) . ',
                    backgroundColor: ' . json_encode($colores) . ',
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Ventas por Región (semaforizado por % de ganancia)" }
                },
                scales: {
                    y: { beginAtZero: true }
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
    // Si se seleccionó un producto, mostrar clientes y cantidades
    if ($producto) {
        // Datos ficticios de clientes y cantidades por producto
        $clientes_producto = [
            'Pelota' => ['Juan' => 3, 'Ana' => 7, 'Carlos' => 1],
            'Zapatillas' => ['Juan' => 1, 'Ana' => 2],
            'Remera' => ['Carlos' => 2, 'Ana' => 5],
            'Short' => ['Lucía' => 2, 'Pedro' => 1],
            'Medias' => ['Lucía' => 1, 'Pedro' => 2],
            'Botines' => ['María' => 1, 'José' => 2],
            'Gorra' => ['María' => 2, 'José' => 1],
        ];
        $clientes = $clientes_producto[$producto] ?? [];
        // Semaforización por ranking de cantidad comprada
        $cantidades_cli = array_values($clientes);
        $clientes_ordenados = array_keys($clientes);
        // Ordenar por cantidad descendente
        arsort($clientes);
        $clientes_ordenados = array_keys($clientes);
        $cantidades_cli = array_values($clientes);
        $colores_cli = [];
        $n = count($clientes);
        for ($i = 0; $i < $n; $i++) {
            if ($i === 0) {
                $colores_cli[] = '#22c55e'; // verde (más compró)
            } elseif ($i === 1) {
                $colores_cli[] = '#eab308'; // amarillo (segundo)
            } else {
                $colores_cli[] = '#ef4444'; // rojo (menos compró)
            }
        }
        echo '<a class="back" href="?region=' . urlencode($region) . '&sucursal=' . urlencode($sucursal) . '">← Volver a productos</a>';
        echo '<h2>Clientes que adquirieron ' . htmlspecialchars($producto) . '</h2>';
        if ($clientes) {
            // Gráfico de barras con semaforización por ranking
            echo '<div class="chart-container"><canvas id="cliProdChart"></canvas></div>';
            echo '<script>
                const ctxCliProd = document.getElementById("cliProdChart").getContext("2d");
                new Chart(ctxCliProd, {
                    type: "bar",
                    data: {
                        labels: ' . json_encode($clientes_ordenados) . ',
                        datasets: [{
                            label: "Cantidad Comprada",
                            data: ' . json_encode($cantidades_cli) . ',
                            backgroundColor: ' . json_encode($colores_cli) . ',
                        }]
                    },
                    options: {
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: "Cantidad comprada por cliente (ranking color)" }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            </script>';
            // Tabla
            echo '<table style="width:100%;border-collapse:collapse;margin-bottom:2rem;">';
            echo '<tr style="background:#f5f7f8;"><th style="text-align:left;padding:8px;">Cliente</th><th style="text-align:right;padding:8px;">Cantidad</th></tr>';
            for ($i = 0; $i < $n; $i++) {
                echo '<tr><td style="padding:8px;">' . htmlspecialchars($clientes_ordenados[$i]) . '</td><td style="text-align:right;padding:8px;color:' . $colores_cli[$i] . ';font-weight:bold;">' . $cantidades_cli[$i] . '</td></tr>';
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
            $sql = "SELECT p.nombre AS producto, SUM(v.cantidad) AS cantidad, SUM(v.total) AS total
                    FROM Venta v
                    JOIN Producto p ON p.id = v.producto_id
                    GROUP BY p.id, p.nombre
                    ORDER BY cantidad DESC";
            $res = $conn->query($sql);
            $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Exception $e) {
            $rows = [];
        }
        if (!empty($rows)) {
            $labels = array_map(fn($r) => $r['producto'], $rows);
            $cant   = array_map(fn($r) => (int)$r['cantidad'], $rows);
            echo '<h2>Ventas por producto (datos reales)</h2>';
            echo '<div class="chart-container"><canvas id="ventasProd"></canvas></div>';
            echo '<ul>';
            foreach ($rows as $r) {
                echo '<li>' . htmlspecialchars($r['producto']) . ' — Cant: <strong>' . (int)$r['cantidad'] . '</strong> — Total: <strong>$' . number_format((float)$r['total'], 2) . '</strong></li>';
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
                            backgroundColor: "#0b73da"
                        }]
                    },
                    options: {plugins: {legend: {display:false}}, scales: {y: {beginAtZero:true}}}
                });
            </script>';
            echo '<hr style="margin:2rem 0;opacity:.25">';
        }
    }
    echo '<a class="back" href="?region=' . urlencode($region) . '">← Volver a sucursales</a>';
    echo '<h2>Detalle de ' . htmlspecialchars($sucursal) . ' (' . htmlspecialchars($region) . ')</h2>';
    // Datos ficticios de cantidad y costo de productos vendidos
    $productos_cant = [
        // Centro (Rosario)
        'Pelota' => ['cantidad' => 50, 'venta' => 100, 'costo' => 45], // 55% ganancia
        'Zapatillas' => ['cantidad' => 30, 'venta' => 60, 'costo' => 40], // igual
        'Remera' => ['cantidad' => 20, 'venta' => 40, 'costo' => 52], // -30% ganancia
        // Norte (Rosario)
        'Short' => ['cantidad' => 25, 'venta' => 30, 'costo' => 35],
        'Medias' => ['cantidad' => 15, 'venta' => 20, 'costo' => 25],
        // Sur (Santa Fe)
        'Botines' => ['cantidad' => 10, 'venta' => 10, 'costo' => 15],
        'Gorra' => ['cantidad' => 5, 'venta' => 5, 'costo' => 10],
    ];
    $prods = $detalle['productos'];
    $cantidades = [];
    $porcentajes = [];
    foreach ($prods as $prod) {
        $venta = $productos_cant[$prod]['venta'] ?? rand(10, 100);
        $costo = $productos_cant[$prod]['costo'] ?? rand(5, 90);
        $cantidad = $productos_cant[$prod]['cantidad'] ?? rand(5, 50);
        $cantidades[$prod] = $cantidad;
        $ganancia = $venta - $costo;
        $porcentaje = $venta > 0 ? round(($ganancia / $venta) * 100, 2) : 0;
        $porcentajes[$prod] = $porcentaje;
    }
    // Ordenar de mayor a menor cantidad
    arsort($cantidades);
    // Semaforización por producto
    $colores_prod = [];
    foreach ($cantidades as $prod => $cant) {
        $porcentaje = $porcentajes[$prod];
        if ($porcentaje > 40) {
            $colores_prod[] = "#22c55e"; // verde
        } elseif ($porcentaje >= 0) {
            $colores_prod[] = "#eab308"; // amarillo
        } else {
            $colores_prod[] = "#ef4444"; // rojo
        }
    }
    echo '<div class="chart-container"><canvas id="prodChart"></canvas></div>';
    echo '<ul style="margin-bottom:1.5rem;">';
    foreach ($cantidades as $prod => $cant) {
        echo '<li><a href="?region=' . urlencode($region) . '&sucursal=' . urlencode($sucursal) . '&producto=' . urlencode($prod) . '">' . htmlspecialchars($prod) . '</a> <span style="font-size:0.95rem;color:#555">(' . $porcentajes[$prod] . '%)</span></li>';
    }
    echo '</ul>';
    echo '<script>
        const ctxProd = document.getElementById("prodChart").getContext("2d");
        new Chart(ctxProd, {
            type: "bar",
            data: {
                labels: ' . json_encode(array_keys($cantidades)) . ',
                datasets: [{
                    label: "Cantidad Vendida",
                    data: ' . json_encode(array_values($cantidades)) . ',
                    backgroundColor: ' . json_encode($colores_prod) . ',
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Productos vendidos (semaforizado por % de ganancia)" }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>';
    // Datos ficticios de compras por cliente (ELIMINADO)
    // Solo mostrar el gráfico y lista de productos
    exit;
}

// Si no existe la región o sucursal
http_response_code(404);
echo '<h2>No encontrado</h2>';
?>
</div>
</body>
</html>
