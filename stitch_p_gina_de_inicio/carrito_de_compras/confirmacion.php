<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['compra_exitosa'])) {
    header('Location: ../página_de_inicio/code.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Compra confirmada - FitZone</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700;900&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0b73da",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Lexend"]
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
<div class="relative flex min-h-screen w-full flex-col items-center justify-center">
<div class="text-center space-y-6 max-w-md px-4">
<div class="flex justify-center">
<div class="w-24 h-24 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
<svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
</svg>
</div>
</div>

<h1 class="text-4xl font-bold">¡Compra confirmada!</h1>
<p class="text-lg text-slate-600 dark:text-slate-300">Gracias por tu compra en FitZone</p>

<div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 space-y-4">
<div class="border-b border-slate-200 dark:border-slate-700 pb-4">
<p class="text-sm text-slate-500 dark:text-slate-400">Número de pedido</p>
<p class="text-2xl font-bold text-primary">#<?php echo $_SESSION['id_venta']; ?></p>
</div>

<div>
<p class="text-sm text-slate-500 dark:text-slate-400">Total de la compra</p>
<p class="text-3xl font-bold">$<?php echo number_format($_SESSION['total_venta'], 2); ?></p>
</div>

<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-4">
<p class="text-sm text-blue-800 dark:text-blue-300">Recibirás un correo de confirmación con los detalles de tu pedido. El envío será procesado dentro de 24 horas.</p>
</div>
</div>

<div class="space-y-3 w-full">
<a href="../página_de_inicio/code.php" class="block w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded transition-colors">
Volver a comprar
</a>
<a href="../logout.php" class="block w-full bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 font-bold py-3 rounded transition-colors">
Cerrar sesión
</a>
</div>
</div>
</div>
</body>
</html>
