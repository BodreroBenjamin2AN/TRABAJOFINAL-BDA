<?php
$host = "localhost";
$user = "root";    // <- ajusta si tu usuario es distinto
$pass = "";        // <- ajusta si tu contraseña no está vacía
$dbname = "fitzone";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>