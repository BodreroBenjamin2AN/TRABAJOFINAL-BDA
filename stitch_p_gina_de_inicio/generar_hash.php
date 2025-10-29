<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'] ?? '';
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $message = "Hash generado exitosamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Hash para Contraseñas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Generador de Hash para Contraseñas</h1>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Ingresa la contraseña:</label>
                <input type="text" id="password" name="password" required 
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <button type="submit" 
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Generar Hash
            </button>
        </form>

        <?php if (isset($hashed_password)): ?>
            <div class="mt-6 space-y-4">
                <div class="p-4 bg-green-100 rounded-md">
                    <p class="text-green-700"><?php echo $message; ?></p>
                </div>
                
                <div class="space-y-2">
                    <p class="font-semibold">Contraseña original:</p>
                    <p class="bg-gray-100 p-2 rounded break-all"><?php echo htmlspecialchars($password); ?></p>
                    
                    <p class="font-semibold">Hash generado (copia esto a phpMyAdmin):</p>
                    <p class="bg-gray-100 p-2 rounded break-all"><?php echo htmlspecialchars($hashed_password); ?></p>
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <p class="font-semibold">Instrucciones para agregar usuario en phpMyAdmin:</p>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Ve a phpMyAdmin</li>
                        <li>Selecciona la base de datos 'fitzone'</li>
                        <li>Selecciona la tabla 'Usuario'</li>
                        <li>Click en "Insertar"</li>
                        <li>En el campo 'email' escribe el correo del usuario</li>
                        <li>En el campo 'password' pega el hash generado</li>
                        <li>Click en "Continuar"</li>
                    </ol>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>