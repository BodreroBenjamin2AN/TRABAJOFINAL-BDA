<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

// Obtener productos de la base de datos
$productos = [];
try {
    $resultado = $conn->query("SELECT id, nombre, precio_unitario, stock FROM Producto WHERE nombre IN ('Botines', 'Gorra')");
    if ($resultado) {
        $productos = $resultado->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log("Error al obtener productos: " . $e->getMessage());
}

// Agregar fotos por defecto
$fotos = [
    'Botines' => 'BOTINES5.jpeg',
    'Gorra' => 'GORRA1.jpg'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Lexend%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <title>Stitch Design</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              primary: "#0b73da",
              "background-light": "#f5f7f8",
              "background-dark": "#101922",
            },
            fontFamily: {
              display: ["Lexend"],
            },
            borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
          },
        },
      };
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
<div class="relative flex min-h-screen w-full flex-col group/design-root">
<div class="layout-container flex h-full grow flex-col">
<header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 px-10 py-3">
<div class="flex items-center gap-4">
<svg class="text-primary size-7" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
</svg>
<h2 class="text-xl font-bold">FitZone</h2>
</div>
<nav class="flex items-center gap-6 text-sm font-medium">
<a class="hover:text-primary transition-colors" href="../drilldown_ejemplo.php">Drilldown</a>
<a class="hover:text-primary transition-colors" href="../crear_usuario.php">Crear Usuario</a>
<a class="hover:text-primary transition-colors" href="#">Mujer</a>
<a class="hover:text-primary transition-colors" href="#">Niños</a>
<a class="hover:text-primary transition-colors" href="#">Accesorios</a>
<a class="hover:text-primary transition-colors" href="#">Ofertas</a>
</nav>
<div class="flex items-center gap-4">
<div class="relative">
<button onclick="toggleSearchBar()" class="flex h-10 w-10 items-center justify-center rounded-full bg-background-light dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
<svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg">
<path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
</svg>
</button>
<input type="text" id="searchInput" placeholder="Buscar producto..." class="hidden absolute right-0 top-12 w-48 border-2 border-primary rounded-lg px-4 py-2 bg-white dark:bg-slate-800 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-primary z-50" onkeyup="filtrarProductos(event)">
</div>
<a href="../carrito_de_compras/code.php" class="flex h-10 w-10 items-center justify-center rounded-full bg-background-light dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" title="Carrito">
<svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg">
<path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
</svg>
</a>
<?php if (isset($_SESSION['usuario_id']) || isset($_SESSION['user_id'])): ?>
    <a href="../logout.php" class="flex h-10 px-4 items-center justify-center rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors" title="Cerrar sesión">Salir</a>
<?php else: ?>
    <a href="../login_de_usuario/code.php" class="flex h-10 px-4 items-center justify-center rounded-full bg-primary text-white hover:bg-primary/90 transition-colors" title="Iniciar sesión">Ingresar</a>
<?php endif; ?>
</div>
</header>
<main class="flex-1 px-10 xl:px-40 py-8">
<div class="mx-auto flex max-w-[960px] flex-col">
<div class="w-full">
<div class="relative flex min-h-[480px] flex-col items-center justify-center gap-6 rounded-xl bg-cover bg-center bg-no-repeat p-8 text-center" style='background-image: linear-gradient(rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.5) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCTmbx3KPGgVusvwX0wSycDzXMqxOgTXZQ-bHOEDqaPbFDtZk0fXs0c0C4tj7h9kPe4zFKmf0UjMSA0Ak5PcoHDSjyMXERz69-YV5w7JKJo81xuTzyWjSMYiXZILPJ1Endb5T9qkhLLXJVKCsGI_YqlR6N4idu6yf2qZVhv6yurNc9ceVl6tpjF68pE-aNrxcZkGM2HnQ4F7ZYeINIJbu1LSVqj2UTkNNo5dkUQhJpm2uMSvNQWS4EcXzTomVEii1YsF00pIraXHkwa");'>
<div class="flex flex-col gap-4">
<h1 class="text-white text-4xl font-black md:text-6xl">Equípate para la victoria</h1>
<p class="text-white/90 text-lg md:text-xl">Descubre las últimas novedades y ofertas en calzado, ropa y accesorios deportivos.</p>
</div>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold transition-all hover:bg-primary/90">
<span class="truncate">Comprar ahora</span>
</button>
</div>
</div>
<section class="py-12">
<h2 class="text-3xl font-bold mb-6">Novedades</h2>
<div class="flex snap-x snap-mandatory overflow-x-auto pb-4 -mx-4 px-4 gap-6">
<div class="flex snap-center flex-col gap-4 rounded-lg min-w-72 flex-shrink-0">
<div class="w-full aspect-square bg-cover bg-center rounded-lg" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA6DyrnMicRqtj6kzPs4nLc6d6cbRbOGMSML76ecGMQsmNa7p5Mnvms0np4CHxyGDpQBN5S8Na_kZFswHkIPtTP3oZI-44rrXRgjwgSqBRKEiz8N3q2I-Vo-hw3n71XdqTby1vvCs-x8RFQNVkayO1diE21d9J32c_xjpX-kq48WOFhRTJS9oW3y0X1HaUxXC-s4mQr9_JGYvV-l76bgE6Ktr0dsoS1HmrU1wa2meFfFazL0963_RkULoPUFU_cnIRtqr9x6O2N1Lfn");'></div>
<div>
<h3 class="font-bold text-lg">Calzado de alto rendimiento</h3>
<p class="text-slate-500 dark:text-slate-400">Zapatillas diseñadas para superar tus límites.</p>
</div>
</div>
<div class="flex snap-center flex-col gap-4 rounded-lg min-w-72 flex-shrink-0">
<div class="w-full aspect-square bg-cover bg-center rounded-lg" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC_2mnTgl1fon1K-NZUHWythyPh3ln2zOu5mtZw6pC1fRBPFWG072I2hDr20r1ZuK-Pxi8mAnA0PvRX07JniZb9m8DGii_WSTZDAKmZTSVwBYw7Ruo8JWP7uxrZmN2R2em7euXaMrxmP6Z7rb4DdWaXhKx79igalefSqYBm2SUrEuJC5nuIh8Bbod2wZkUeKxZDk-_oOB8-upc3he4FsgKc5FR27RNyz6E_F7N8Pxzc4KIuJW3W8YmdF6BIsBYh2plCRioJOSmPuv92");'></div>
<div>
<h3 class="font-bold text-lg">Ropa deportiva innovadora</h3>
<p class="text-slate-500 dark:text-slate-400">Ropa que combina estilo y funcionalidad.</p>
</div>
</div>
<div class="flex snap-center flex-col gap-4 rounded-lg min-w-72 flex-shrink-0">
<div class="w-full aspect-square bg-cover bg-center rounded-lg" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC4CeeZbEqH6lbP02_OCDmK6mphYe5UbrKhewuprSRQz1Fy_WdrvOxi2242AKFMKV9JXOL2l7RBow-DfPiiTC5NcWY1681tl5fkzJNHRf499O3z0NjDEItIpiQ8ElSLCFLoMzsOFWpyHeSF1qhUdMlotoaqowKxAK9t32zcmdED6g2LaNOlDjPQWPZDdHVVs7-duzcMF6J7aeHKcZwHkRfSEneYZ40ACR4PMh_DmhwsInqKHdLCMLgjFZAHYL5Y_UFbQ8w9CsV_9Rue");'></div>
<div>
<h3 class="font-bold text-lg">Equipamiento para todos los deportes</h3>
<p class="text-slate-500 dark:text-slate-400">Todo lo que necesitas para tu próximo entrenamiento.</p>
</div>
</div>
</div>
</section>
<section class="py-12">
<h2 class="text-3xl font-bold mb-6">Ofertas destacadas</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="flex flex-col gap-3 group">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBsQxBDgnTJqDVTx8TR4IjzXKsUEfa9sVAQqrJ4_WYAZz2bkuRm16m80oWqDmEWjwdsZ65AmjBbWEYu1Mj8241rHTSHDdH7PaYlBd6w12LVEZ6IAx0wVpkI580s3lGKsxmFwRK4oAWsx_4Itd7fCy4FVamvuQECTW5zTvzOqjIpnCxXJHozR5O0iWBfFZIUz7k5XniEge41_M2yNcY0iq_nbtASetG5o-UusTX39v8wunR9P7WNWri_eA0vaxIJcB02Ct_SuQF0Fud8");'></div>
</div>
<h3 class="font-medium text-lg">Hasta 50% de descuento en calzado</h3>
</div>
<div class="flex flex-col gap-3 group">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA0bZXtKvBCA_FpL5L9hRTq2cDRZiLVdFEbihy3jIAKWay6asvUw4o0pkcJUmKk-s4HrDFubOCD5MQPMbwtYOdPLBWIvh7bDbP2ybihSWnyflOJe3fF6MyXTUhV5IpU9vTPIaLC_YvoLrSyUMQgVCVgnkhepQ8eBirB4sKoCcetcBoKCr9cnlNa-0XxZtpYiC3pGiQtK0sKiPIIVxklEmxeAVSaa0Gpqt6XgKSxd83Hi95AOHGreZNfP4tTbSYvE_vInIYJpdgaXKv7");'></div>
</div>
<h3 class="font-medium text-lg">2x1 en ropa de entrenamiento</h3>
</div>
<div class="flex flex-col gap-3 group">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBocuvdnq4mC-rDo7o_Lq8PqZ4tDymzXhsPp7glJbOK_kVKgGw5aWenya20o4ancpHJBRgosP2BFtvhWwYfAvinXY6ZNXy9zYZZbMSQnu5LtxEJi7hJOI0SCdfoLKi07ZaeAgw9SYy3QVJPD0tWZjMeGNtwygbytmj3ytHhCPOM4oZAUpa7zf3-zwLD6zQhOfqsydm63SApVhEwyIByxBIlNWBwL21qaafQy-kcaRQty7mdXPd9OpHn8q1uSZ-oL-gt3Y9K9ZxLo495");'></div>
</div>
<h3 class="font-medium text-lg">Accesorios deportivos desde 9,99€</h3>
</div>
</div>
</section>
<section class="py-12">
<h2 class="text-3xl font-bold mb-6">Categorías principales</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="flex flex-col gap-3 group">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBb6WL8ZlKcWsfZfgP3hAY4u3qpg0eqJLjeKEEY0666RmhxxrK0CcqyeWqRI0EJCAIgFVWyEUa4HFZ2Hzk56YzV1XMky-sOJT1jhCUVmllPoECnuTQDLE40JOTzkLftuVXx6HWbKaS955BWF0rcrNhyLO6uDSY8xnUfJaSnRY2NWSR21hmluunjUNW8meFWpzjcGPjzRgSgsmkbcAV5DzvbPAvpz-CipfW6vCKDIgaMvKcjtlyX9QkYcRyss-if2ooLlityFSxlZjra");'></div>
</div>
<h3 class="font-medium text-lg">Calzado</h3>
</div>
<div class="flex flex-col gap-3 group">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDLaFxekAv0oJjdWqEH3MyjseuAM0-aHTSIeYlqPl-KiN8bE5MpHWH5-jn4cfGrum8LbCDGptJOhLLgj63Un1I8GForys_IMPvU2JXsMGol0NoWZ3dHZPXLD5EbuUjCGtc6otySlzX_1jpEUzmRYk8VFQ3zoRUHNMcfKx-Dj1VOIds1V5-3QUeGsaTbqARr_opeJm5_Q7vEvOTvhEpmdDyFAkp4uapuGlIXWrqgaA7M7PAa8rXbs8wuTGh1xVi1pKmykQNMBaPhKs4x");'></div>
</div>
<h3 class="font-medium text-lg">Indumentaria</h3>
</div>
<div class="flex flex-col gap-3 group">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAezLQusi-4htYnIAQNXsjx2O56UyCbcZ1tV_vd4PzxQ7A0w_IRA-m6wuWdAMt_NPSIW81TGsYw4turB3SL9HdvKxPgDrrOJPn-zmP6SMyztU-ZfvU48i2jPQ6jl4VXjSLvl7y3q_Lop5fcPkIpe7ye1gEWlwqwWyVoFvBNzE9ItCNabUXovs76G4rRwJJ_nSCUWq-4vn7XNnWQYydB96gnVIhoXK-M1tVh43D3yBWxJAgpaic07SPpmKiCGrgMlHmNUDkcKthZXwGT");'></div>
</div>
<h3 class="font-medium text-lg">Equipamiento deportivo</h3>
</div>
</div>
</section>
<section class="py-12">
<h2 class="text-3xl font-bold mb-6">Productos destacados</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
<?php foreach ($productos as $producto): ?>
<div class="flex flex-col gap-3 group cursor-pointer" data-producto-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>" onclick="abrirModal(<?php echo htmlspecialchars(json_encode($producto)); ?>, '<?php echo htmlspecialchars($fotos[$producto['nombre']] ?? ''); ?>')">
<div class="w-full aspect-square bg-cover bg-center rounded-lg overflow-hidden">
<div class="w-full h-full bg-center bg-no-repeat bg-cover group-hover:scale-105 transition-transform duration-300" style='background-image: url("<?php echo htmlspecialchars($fotos[$producto['nombre']] ?? ''); ?>");'></div>
</div>
<h3 class="font-bold text-lg"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
<p class="text-slate-500 dark:text-slate-400">Precio: $<?php echo number_format($producto['precio_unitario'], 2); ?></p>
<p class="text-sm <?php echo $producto['stock'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
<?php echo $producto['stock'] > 0 ? 'Stock: ' . $producto['stock'] : 'Sin stock'; ?>
</p>
</div>
<?php endforeach; ?>
</div>
</section>
</div>
</main>
<footer class="bg-background-light dark:bg-background-dark border-t border-slate-200 dark:border-slate-800">
<div class="mx-auto max-w-[960px] px-5 py-10">
<div class="flex flex-col items-center gap-8 text-center sm:flex-row sm:justify-between">
<div class="flex flex-wrap justify-center gap-x-6 gap-y-4">
<a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="#">Política de privacidad</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="#">Términos y condiciones</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="#">Contacto</a>
</div>
<div class="flex justify-center gap-4">
<a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="#">
<svg fill="currentColor" height="24px" viewBox="0 0 256 256" width="24px" xmlns="http://www.w3.org/2000/svg">
<path d="M247.39,68.94A8,8,0,0,0,240,64H209.57A48.66,48.66,0,0,0,168.1,40a46.91,46.91,0,0,0-33.75,13.7A47.9,47.9,0,0,0,120,88v6.09C79.74,83.47,46.81,50.72,46.46,50.37a8,8,0,0,0-13.65,4.92c-4.31,47.79,9.57,79.77,22,98.18a110.93,110.93,0,0,0,21.88,24.2c-15.23,17.53-39.21,26.74-39.47,26.84a8,8,0,0,0-3.85,11.93c.75,1.12,3.75,5.05,11.08,8.72C53.51,229.7,65.48,232,80,232c70.67,0,129.72-54.42,135.75-124.44l29.91-29.9A8,8,0,0,0,247.39,68.94Zm-45,29.41a8,8,0,0,0-2.32,5.14C196,166.58,143.28,216,80,216c-10.56,0-18-1.4-23.22-3.08,11.51-6.25,27.56-17,37.88-32.48A8,8,0,0,0,92,169.08c-.47-.27-43.91-26.34-44-96,16,13,45.25,33.17,78.67,38.79A8,8,0,0,0,136,104V88a32,32,0,0,1,9.6-22.92A30.94,30.94,0,0,1,167.9,56c12.66.16,24.49,7.88,29.44,19.21A8,8,0,0,0,204.67,80h16Z"></path>
</svg>
</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="#">
<svg fill="currentColor" height="24px" viewBox="0 0 256 256" width="24px" xmlns="http://www.w3.org/2000/svg">
<path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"></path>
</svg>
</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" target="_blank" href="#">
<svg fill="currentColor" height="24px" viewBox="0 0 256 256" width="24px" xmlns="http://www.w3.org/2000/svg">
<path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path>
</svg>
</a>
</div>
</div>
<p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">© 2025 FitZone. Todos los derechos reservados.</p>
</div>
</footer>
</div>
</div>

<!-- Modal de Compra -->
<div id="modalCompra" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div class="bg-white dark:bg-slate-800 rounded-lg p-8 max-w-md w-full mx-4 shadow-lg">
<h2 id="modalProducto" class="text-2xl font-bold mb-4">Producto</h2>
<div id="modalFoto" class="w-full aspect-square bg-cover rounded-lg mb-4"></div>
<p id="modalPrecio" class="text-lg font-semibold mb-2">Precio: $0.00</p>
<p id="modalStock" class="text-sm mb-4">Stock disponible</p>
<div class="mb-4">
<label class="block text-sm font-medium mb-2">Cantidad:</label>
<input type="number" id="cantidadCompra" value="1" min="1" max="10" class="w-full border rounded px-3 py-2 dark:bg-slate-700 dark:border-slate-600">
</div>
<div class="flex gap-4">
<button onclick="cerrarModal()" class="flex-1 bg-gray-300 dark:bg-slate-600 hover:bg-gray-400 text-black dark:text-white font-bold py-2 rounded transition-colors">
Cancelar
</button>
<button onclick="comprar()" class="flex-1 bg-primary hover:bg-primary/90 text-white font-bold py-2 rounded transition-colors">
Agregar al carrito
</button>
</div>
</div>
</div>

<script>
let productoActual = null;

function abrirModal(producto, foto) {
    productoActual = producto;
    // Resolver la URL absoluta de la imagen para que funcione también en el carrito
    try {
        const urlAbsoluta = new URL(foto, document.baseURI).href;
        fotoActual = urlAbsoluta; // Guardar la foto en variable global ya resuelta
        console.log('Foto resuelta a URL absoluta:', fotoActual);
    } catch (e) {
        // Si por alguna razón falla, usar el valor original
        fotoActual = foto;
        console.warn('No se pudo resolver URL absoluta de la foto, usando original:', foto);
    }
    document.getElementById('modalProducto').textContent = producto.nombre;
    document.getElementById('modalPrecio').textContent = `Precio: $${parseFloat(producto.precio_unitario).toFixed(2)}`;
    document.getElementById('modalStock').textContent = `Stock: ${producto.stock} disponibles`;
    document.getElementById('modalFoto').style.backgroundImage = `url('${fotoActual}')`;
    document.getElementById('cantidadCompra').max = producto.stock;
    document.getElementById('modalCompra').classList.remove('hidden');
    console.log('Modal abierto con foto:', foto);
}

function cerrarModal() {
    document.getElementById('modalCompra').classList.add('hidden');
    productoActual = null;
}

let fotoActual = ''; // Variable global para guardar la foto

function comprar() {
    if (!productoActual) return;
    
    const cantidad = parseInt(document.getElementById('cantidadCompra').value);
    if (cantidad < 1 || cantidad > productoActual.stock) {
        alert('Cantidad inválida');
        return;
    }
    
    // Guardar en carrito (incluir foto)
    const datosCompra = {
        productId: productoActual.id,
        nombre: productoActual.nombre,
        cantidad: cantidad,
        precio: parseFloat(productoActual.precio_unitario),
        total: cantidad * parseFloat(productoActual.precio_unitario),
        foto: fotoActual
    };
    
    console.log('Datos a guardar:', datosCompra);
    
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.push(datosCompra);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    
    console.log('Carrito actualizado:', carrito);
    console.log('localStorage ahora contiene:', localStorage.getItem('carrito'));
    
    alert(`${cantidad} ${productoActual.nombre}(s) agregado(s) al carrito`);
    cerrarModal();
    
    // Redirigir al carrito
    window.location.href = '../carrito_de_compras/code.php';
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalCompra').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

// Funciones de búsqueda
function toggleSearchBar() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput.classList.contains('hidden')) {
        searchInput.classList.remove('hidden');
        searchInput.focus();
    } else {
        searchInput.classList.add('hidden');
        searchInput.value = '';
    }
}

function filtrarProductos(event) {
    if (event.key === 'Enter') {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const productos = document.querySelectorAll('[data-producto-nombre]');
        let encontrado = false;
        
        for (let producto of productos) {
            const nombre = producto.getAttribute('data-producto-nombre').toLowerCase();
            if (nombre.includes(query)) {
                producto.scrollIntoView({ behavior: 'smooth', block: 'center' });
                producto.style.backgroundColor = 'rgba(11, 115, 218, 0.1)';
                setTimeout(() => {
                    producto.style.backgroundColor = '';
                }, 2000);
                encontrado = true;
                break;
            }
        }
        
        if (!encontrado && query.trim() !== '') {
            alert('Producto no encontrado: ' + query);
        }
        
        document.getElementById('searchInput').classList.add('hidden');
        document.getElementById('searchInput').value = '';
    }
}

// Cerrar búsqueda al hacer clic fuera
document.addEventListener('click', function(e) {
    const searchContainer = document.querySelector('.relative');
    if (searchContainer && !searchContainer.contains(e.target)) {
        const searchInput = document.getElementById('searchInput');
        if (searchInput && !searchInput.classList.contains('hidden')) {
            searchInput.classList.add('hidden');
        }
    }
});
</script>

</body></html>