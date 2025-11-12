<?php
session_start();
// Borrar claves conocidas
unset($_SESSION['usuario_id'], $_SESSION['user_id']);
// Destruir la sesión
session_destroy();
// Volver a la página de inicio
header("Location: página_de_inicio/code.php");
exit();
?>