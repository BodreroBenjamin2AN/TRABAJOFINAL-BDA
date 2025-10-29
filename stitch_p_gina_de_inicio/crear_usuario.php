<?php
include 'db.php';

$email = 'admin@fitzone.com';
$password = 'fitzone123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Primero, eliminar el usuario si existe
    $stmt = $conn->prepare("DELETE FROM Usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // Luego, insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO Usuario (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    
    echo "Usuario creado exitosamente.<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Contraseña (sin hash): " . htmlspecialchars($password) . "<br>";
    echo "Contraseña (con hash): " . htmlspecialchars($hashed_password) . "<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>