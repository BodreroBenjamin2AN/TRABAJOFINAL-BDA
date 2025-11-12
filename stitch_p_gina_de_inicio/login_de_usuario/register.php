<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$err = $_SESSION['register_error'] ?? null;
$old_email = $_SESSION['register_old_email'] ?? '';
unset($_SESSION['register_error'], $_SESSION['register_old_email']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FitZone - Registro</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: { primary: "#0b73da", "background-light": "#f5f7f8", "background-dark": "#101922" },
          fontFamily: { display: ["Lexend"] },
          borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
        },
      },
    }
  </script>
  <style>
    /* Imagen de fondo dentro de los campos */
    .input-with-photo {
      background-image: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)), url('campo_bg.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      color: #111827; /* slate-900 */
    }
    .input-with-photo::placeholder {
      color: #6b7280; /* slate-500 */
      opacity: 1;
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
  <div class="flex min-h-screen items-center justify-center px-4">
    <div class="w-full max-w-md bg-white dark:bg-background-dark rounded-xl shadow p-8 space-y-6">
      <h1 class="text-2xl font-bold text-center">Crear cuenta</h1>
      <?php if ($err): ?>
        <div class="text-sm text-red-700 bg-red-100 p-2 rounded"><?php echo htmlspecialchars($err); ?></div>
      <?php endif; ?>
      <form action="register_action.php" method="POST" class="space-y-4">
        <div>
          <label class="sr-only" for="email">Email</label>
          <input class="block w-full rounded-md border-gray-300 py-2 px-3 input-with-photo" type="email" id="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($old_email); ?>" />
        </div>
        <div>
          <label class="sr-only" for="password">Contraseña</label>
          <input class="block w-full rounded-md border-gray-300 py-2 px-3 input-with-photo" type="password" id="password" name="password" minlength="6" required placeholder="Contraseña" />
        </div>
        <div>
          <label class="sr-only" for="password2">Repetir contraseña</label>
          <input class="block w-full rounded-md border-gray-300 py-2 px-3 input-with-photo" type="password" id="password2" name="password2" minlength="6" required placeholder="Repetir contraseña" />
        </div>
        <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded">Registrarme</button>
      </form>
      <p class="text-center text-sm">¿Ya tenés cuenta? <a href="code.php" class="text-primary hover:underline">Inicia sesión</a></p>
    </div>
  </div>
</body>
</html>
