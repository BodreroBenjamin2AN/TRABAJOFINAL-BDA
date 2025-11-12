<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    // Validaciones básicas
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Email inválido.';
        $_SESSION['register_old_email'] = $email;
        header('Location: register.php');
        exit;
    }
    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'La contraseña debe tener al menos 6 caracteres.';
        $_SESSION['register_old_email'] = $email;
        header('Location: register.php');
        exit;
    }
    if ($password !== $password2) {
        $_SESSION['register_error'] = 'Las contraseñas no coinciden.';
        $_SESSION['register_old_email'] = $email;
        header('Location: register.php');
        exit;
    }

    // Verificar si el email ya existe
    $stmt = $conn->prepare('SELECT id FROM Usuario WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['register_error'] = 'Este email ya está registrado.';
        $_SESSION['register_old_email'] = $email;
        $stmt->close();
        header('Location: register.php');
        exit;
    }
    $stmt->close();

    // Crear usuario
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO Usuario (email, password) VALUES (?, ?)');
    $stmt->bind_param('ss', $email, $hash);
    if (!$stmt->execute()) {
        $_SESSION['register_error'] = 'Error al registrar usuario.';
        $_SESSION['register_old_email'] = $email;
        $stmt->close();
        header('Location: register.php');
        exit;
    }
    $newId = $stmt->insert_id;
    $stmt->close();

    // Loguear y redirigir a inicio
    $_SESSION['usuario_id'] = $newId;
    header('Location: ../página_de_inicio/code.php');
    exit;
}

header('Location: register.php');
exit;
