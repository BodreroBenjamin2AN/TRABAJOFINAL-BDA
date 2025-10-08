<?php
$host = "localhost";
$user = "tu_usuario";
$pass = "tu_contraseña";
$dbname = "fitzone";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
