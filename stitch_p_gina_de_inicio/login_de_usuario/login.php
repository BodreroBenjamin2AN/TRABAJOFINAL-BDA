<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM Usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            // Login correcto: limpiar errores previos y redirigir
            unset($_SESSION['login_error'], $_SESSION['old_email']);
            $_SESSION['usuario_id'] = $id;
            header("Location: ../página_de_inicio/code.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta.";
            $_SESSION['old_email'] = $email;
        }
    } else {
        $_SESSION['login_error'] = "Usuario no encontrado.";
        $_SESSION['old_email'] = $email;
    }

    $stmt->close();
    $conn->close();

    // Volver al formulario para mostrar mensaje
    header("Location: code.php");
    exit();
}
?>