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
            'ventas' => 120,
        ],
        'Norte' => [
            'productos' => ['Short', 'Medias'],
            'clientes' => ['Lucía', 'Pedro'],
            'ventas' => 80,
        ],
    ],
    'Santa Fe' => [
        'Sur' => [
            'productos' => ['Botines', 'Gorra'],
            'clientes' => ['María', 'José'],
            'ventas' => 60,
        ],
    ],
];

// Nivel 1: Mostrar regiones con gráfico
if (!isset($_GET['region'])) {
    // Sumar ventas por región
    $ventas_por_region = [];
    foreach ($regiones as $region => $sucursales) {
        $ventas = 0;
        foreach ($sucursales as $detalle) {
            $ventas += $detalle['ventas'];
        }
        $ventas_por_region[$region] = $ventas;
    }
    echo '<h2>Ventas por Región</h2>';
    echo '<div class="chart-container"><canvas id="regionChart"></canvas></div>';
    echo '<ul>';
    foreach ($regiones as $region => $sucursales) {
        echo '<li><a href="?region=' . urlencode($region) . '">' . htmlspecialchars($region) . '</a></li>';
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
                    backgroundColor: ["#0b73da", "#1877f2"],
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Ventas por Región" }
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
if ($sucursal && isset($regiones[$region][$sucursal])) {
    $detalle = $regiones[$region][$sucursal];
    echo '<a class="back" href="?region=' . urlencode($region) . '">← Volver a sucursales</a>';
    echo '<h2>Detalle de ' . htmlspecialchars($sucursal) . ' (' . htmlspecialchars($region) . ')</h2>';
    // Datos ficticios de cantidad de productos vendidos
    $productos_cant = [
        'Pelota' => 50,
        'Zapatillas' => 30,
        'Remera' => 20,
        'Short' => 25,
        'Medias' => 15,
        'Botines' => 10,
        'Gorra' => 5,
    ];
    // Filtrar solo los productos de la sucursal
    $prods = $detalle['productos'];
    $cantidades = [];
    foreach ($prods as $prod) {
        $cantidades[$prod] = $productos_cant[$prod] ?? rand(5, 50);
    }
    // Ordenar de mayor a menor
    arsort($cantidades);
    echo '<div class="chart-container"><canvas id="prodChart"></canvas></div>';
    echo '<script>
        const ctxProd = document.getElementById("prodChart").getContext("2d");
        new Chart(ctxProd, {
            type: "bar",
            data: {
                labels: ' . json_encode(array_keys($cantidades)) . ',
                datasets: [{
                    label: "Cantidad Vendida",
                    data: ' . json_encode(array_values($cantidades)) . ',
                    backgroundColor: "#0b73da",
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Productos vendidos (mayor a menor)" }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>';
    // Datos ficticios de compras por cliente
    $clientes_cant = [
        'Juan' => 7,
        'Ana' => 12,
        'Carlos' => 5,
        'Lucía' => 9,
        'Pedro' => 3,
        'María' => 8,
        'José' => 4,
    ];
    $clientes = $detalle['clientes'];
    $compras = [];
    foreach ($clientes as $cli) {
        $compras[$cli] = $clientes_cant[$cli] ?? rand(1, 15);
    }
    arsort($compras);
    echo '<div class="chart-container"><canvas id="cliChart"></canvas></div>';
    echo '<script>
        const ctxCli = document.getElementById("cliChart").getContext("2d");
        new Chart(ctxCli, {
            type: "bar",
            data: {
                labels: ' . json_encode(array_keys($compras)) . ',
                datasets: [{
                    label: "Cantidad de Compras",
                    data: ' . json_encode(array_values($compras)) . ',
                    backgroundColor: "#1877f2",
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Compras por Cliente (mayor a menor)" }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>';
    exit;
}

// Si no existe la región o sucursal
http_response_code(404);
echo '<h2>No encontrado</h2>';
?>
</div>
</body>
</html>
