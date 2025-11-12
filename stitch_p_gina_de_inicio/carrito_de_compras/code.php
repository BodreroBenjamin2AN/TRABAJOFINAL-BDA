<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

// El carrito funciona con localStorage, no requiere autenticación para ver
// La autenticación se verificará al procesar el pago
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>FitZone - Carrito</title>
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
<div class="relative flex min-h-screen w-full flex-col">
<div class="layout-container flex h-full grow flex-col">
<header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 px-10 py-3">
<div class="flex items-center gap-4">
<svg class="text-primary size-7" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
</svg>
<h2 class="text-xl font-bold">FitZone</h2>
</div>
<nav class="flex items-center gap-6 text-sm font-medium">
<a class="hover:text-primary transition-colors" href="../página_de_inicio/code.php">Inicio</a>
<a class="hover:text-primary transition-colors" href="#">Hombre</a>
<a class="hover:text-primary transition-colors" href="#">Mujer</a>
<a class="hover:text-primary transition-colors" href="#">Niños</a>
<a class="hover:text-primary transition-colors" href="#">Accesorios</a>
</nav>
<div class="flex items-center gap-4">
<a href="code.php" class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white hover:bg-primary/90 transition-colors">
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
<div class="mb-8">
<h1 class="text-4xl font-bold mb-2">Carrito de compras</h1>
<p class="text-slate-500 dark:text-slate-400"><a href="../página_de_inicio/code.php" class="text-primary hover:underline">Inicio</a> / Carrito</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Productos en carrito -->
<div class="lg:col-span-2">
<div id="carritoVacio" class="text-center py-12">
<p class="text-lg text-slate-500 dark:text-slate-400">Tu carrito está vacío</p>
<a href="../página_de_inicio/code.php" class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded hover:bg-primary/90">Continuar comprando</a>
</div>

<div id="carritoConProductos" class="hidden space-y-4">
<div class="hidden sm:grid grid-cols-12 gap-4 px-4 py-2 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase text-slate-500 dark:text-slate-400">
<span class="col-span-6">Producto</span>
<span class="col-span-2 text-center">Precio</span>
<span class="col-span-2 text-center">Cantidad</span>
<span class="col-span-2 text-right">Subtotal</span>
</div>

<div id="items-carrito"></div>
</div>
</div>

<!-- Resumen de compra -->
<div class="lg:col-span-1">
<div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 space-y-6 border border-slate-200 dark:border-slate-800 sticky top-20">
<h3 class="text-xl font-bold">Resumen</h3>
<div class="space-y-3 text-sm">
<div class="flex justify-between">
<span class="text-slate-600 dark:text-slate-300">Subtotal</span>
<span id="subtotal" class="font-medium">$0.00</span>
</div>
<div class="flex justify-between">
<span class="text-slate-600 dark:text-slate-300">Envío</span>
<span class="font-medium">Gratis</span>
</div>
<div class="flex justify-between">
<span class="text-slate-600 dark:text-slate-300">Impuesto (10%)</span>
<span id="impuesto" class="font-medium">$0.00</span>
</div>
</div>
<div class="border-t border-slate-200 dark:border-slate-800 pt-4">
<div class="flex justify-between font-bold text-lg mb-6">
<span>Total</span>
<span id="total">$0.00</span>
</div>
</div>
<form method="POST" action="procesar_pago.php" id="formularioCompra">
<button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded transition-colors">
Proceder al pago
</button>
</form>
<button onclick="vaciarCarrito()" class="w-full bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 font-bold py-2 rounded transition-colors">
Vaciar carrito
</button>
</div>
</div>
</div>
</div>
</main>

<footer class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-12">
<div class="mx-auto max-w-[960px] px-5 py-10">
<p class="text-center text-sm text-slate-500 dark:text-slate-400">© 2024 FitZone. Todos los derechos reservados.</p>
</div>
</footer>
</div>
</div>

<script>
// Cargar carrito desde localStorage
function cargarCarrito() {
    console.log('Ejecutando cargarCarrito()...');
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    console.log('Carrito leído:', carrito);
    console.log('Cantidad de items:', carrito.length);
    
    const itemsDiv = document.getElementById('items-carrito');
    const carritoVacio = document.getElementById('carritoVacio');
    const carritoConProductos = document.getElementById('carritoConProductos');
    
    if (carrito.length === 0) {
        console.log('Carrito vacío, mostrando mensaje');
        itemsDiv.innerHTML = '';
        carritoVacio.classList.remove('hidden');
        carritoConProductos.classList.add('hidden');
        return;
    }
    
    console.log('Carrito con productos, ocultando mensaje vacío');
    
    carritoVacio.classList.add('hidden');
    carritoConProductos.classList.remove('hidden');
    
    itemsDiv.innerHTML = '';
    let subtotal = 0;
    
    carrito.forEach((item, index) => {
        const itemTotal = item.precio * item.cantidad;
        subtotal += itemTotal;
        
        const itemHTML = `
        <div class="grid grid-cols-12 items-center gap-4 p-4 border-b border-slate-200 dark:border-slate-800">
            <div class="col-span-12 sm:col-span-6 flex items-center gap-4">
                <div class="w-16 h-16 rounded-lg bg-cover bg-center" style='background-image: url("${item.foto}");'></div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200">${item.nombre}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">ID: ${item.productId}</p>
                </div>
            </div>
            <div class="col-span-4 sm:col-span-2 text-center font-medium">$${item.precio.toFixed(2)}</div>
            <div class="col-span-4 sm:col-span-2 flex justify-center items-center gap-2">
                <button class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600" onclick="cambiarCantidad(${index}, -1)">-</button>
                <span class="w-8 text-center font-medium">${item.cantidad}</span>
                <button class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600" onclick="cambiarCantidad(${index}, 1)">+</button>
            </div>
            <div class="col-span-4 sm:col-span-2 text-right font-bold text-slate-800 dark:text-slate-200">$${itemTotal.toFixed(2)}</div>
            <div class="col-span-12 sm:col-span-12 flex justify-end">
                <button class="text-sm text-red-500 hover:underline" onclick="eliminarDelCarrito(${index})">Eliminar</button>
            </div>
        </div>
        `;
        itemsDiv.innerHTML += itemHTML;
    });
    
    const impuesto = subtotal * 0.10;
    const total = subtotal + impuesto;
    
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('impuesto').textContent = '$' + impuesto.toFixed(2);
    document.getElementById('total').textContent = '$' + total.toFixed(2);
}

function cambiarCantidad(index, cambio) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito[index].cantidad += cambio;
    
    if (carrito[index].cantidad < 1) {
        carrito.splice(index, 1);
    }
    
    localStorage.setItem('carrito', JSON.stringify(carrito));
    cargarCarrito();
}

function eliminarDelCarrito(index) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.splice(index, 1);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    cargarCarrito();
}

function vaciarCarrito() {
    if (confirm('¿Estás seguro de que deseas vaciar el carrito?')) {
        localStorage.removeItem('carrito');
        cargarCarrito();
    }
}

// Guardar carrito a sesión antes de enviar formulario
document.getElementById('formularioCompra').addEventListener('submit', function(e) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    if (carrito.length === 0) {
        e.preventDefault();
        alert('El carrito está vacío');
    } else {
        // Crear un campo oculto con el carrito
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'carritoData';
        input.value = JSON.stringify(carrito);
        this.appendChild(input);
    }
});

// Cargar al abrir la página
cargarCarrito();
</script>

</body>
</html>
