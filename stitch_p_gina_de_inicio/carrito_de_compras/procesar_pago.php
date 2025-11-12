<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

// Redirigir si no hay sesión (aceptar ambas claves de sesión: usuario_id o user_id)
$id_usuario = null;
if (isset($_SESSION['usuario_id'])) {
    $id_usuario = (int)$_SESSION['usuario_id'];
} elseif (isset($_SESSION['user_id'])) {
    $id_usuario = (int)$_SESSION['user_id'];
}

if (!$id_usuario) {
    header('Location: ../login_de_usuario/code.php');
    exit;
}

// El carrito viene desde localStorage en el cliente
// Lo pasamos como JSON por POST
$carrito_json = isset($_POST['carritoData']) ? $_POST['carritoData'] : '';
$carrito = $carrito_json ? json_decode($carrito_json, true) : [];

// Si no hay carrito en POST, intentar obtener del localStorage via JavaScript
if (empty($carrito)) {
    echo '<script>
        const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        if (carrito.length === 0) {
            alert("Carrito vacío");
            window.location.href = "code.php";
        } else {
            // Redirigir nuevamente con datos
            const form = document.createElement("form");
            form.method = "POST";
            form.innerHTML = \'<input type="hidden" name="carritoData" value="\' + JSON.stringify(carrito) + \'\">\';
            document.body.appendChild(form);
            form.submit();
        }
    </script>';
    exit;
}

$total_venta = 0.0;
$fecha_venta = date('Y-m-d'); // coincide con columna DATE `fecha` del esquema existente

// Comenzar transacción
$conn->begin_transaction();

try {
    // Preparar statements según esquema actual de `Venta`
    // Insertaremos una fila por ítem comprado (fecha, producto_id, cantidad, total)
    $stmt_insert = $conn->prepare("INSERT INTO Venta (fecha, producto_id, cantidad, total) VALUES (?, ?, ?, ?)");
    $stmt_stock  = $conn->prepare("UPDATE Producto SET stock = stock - ? WHERE id = ?");

    foreach ($carrito as $item) {
        $id_producto = (int)$item['productId'];
        $cantidad    = (int)$item['cantidad'];
        $precio      = (float)$item['precio'];
        $subtotal    = $precio * $cantidad;
        $total_venta += $subtotal;

        // Insertar registro de venta (usando columnas presentes en schema.sql)
        $stmt_insert->bind_param('siid', $fecha_venta, $id_producto, $cantidad, $subtotal);
        $stmt_insert->execute();
        $last_id = $stmt_insert->insert_id; // usaremos el último como número de pedido

        // Actualizar stock
        $stmt_stock->bind_param('ii', $cantidad, $id_producto);
        $stmt_stock->execute();
    }

    $stmt_insert->close();
    $stmt_stock->close();

    // Confirmar transacción
    $conn->commit();

    // Guardar en sesión y redirigir
    $_SESSION['compra_exitosa'] = true;
    $_SESSION['id_venta'] = $last_id ?? 0; // número de la última fila insertada
    $_SESSION['total_venta'] = $total_venta;

    // Limpiar localStorage y redirigir
    echo '<script>
        localStorage.removeItem("carrito");
        window.location.href = "confirmacion.php";
    </script>';

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = 'Error en la compra: ' . $e->getMessage();
    echo '<script>
        alert("Error: ' . addslashes($e->getMessage()) . '");
        window.location.href = "code.php";
    </script>';
    exit;
}
?>
