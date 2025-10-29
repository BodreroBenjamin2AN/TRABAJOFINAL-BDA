<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = $_SESSION['login_error'] ?? null;
$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['login_error'], $_SESSION['old_email']);
?>

<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>FitZone - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
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
          borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
        },
      },
    }
  </script>
<style>
    body {
      font-family: 'Lexend', sans-serif;
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex flex-col min-h-screen">
<header class="w-full">
<nav class="container mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-20 border-b border-gray-200 dark:border-gray-700">
<div class="flex items-center">
<div class="flex-shrink-0 flex items-center gap-2">
<svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_6_535)">
<path clip-rule="evenodd" d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z" fill="currentColor" fill-rule="evenodd"></path>
</g>
<defs>
<clipPath id="clip0_6_535">
<rect fill="white" height="48" width="48"></rect>
</clipPath>
</defs>
</svg>
<span class="text-2xl font-bold text-gray-900 dark:text-white">FitZone</span>
</div>
</div>
<div class="flex items-center space-x-4">
<button class="p-2 rounded-lg bg-background-light dark:bg-background-dark hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors">
<svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg">
<path d="M178,32c-20.65,0-38.73,8.88-50,23.89C116.73,40.88,98.65,32,78,32A62.07,62.07,0,0,0,16,94c0,70,103.79,126.66,108.21,129a8,8,0,0,0,7.58,0C136.21,220.66,240,164,240,94A62.07,62.07,0,0,0,178,32ZM128,206.8C109.74,196.16,32,147.69,32,94A46.06,46.06,0,0,1,78,48c19.45,0,35.78,10.36,42.6,27a8,8,0,0,0,14.8,0c6.82-16.67,23.15-27,42.6-27a46.06,46.06,0,0,1,46,46C224,147.61,146.24,196.15,128,206.8Z"></path>
</svg>
</button>
<button class="p-2 rounded-lg bg-background-light dark:bg-background-dark hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors">
<svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg">
<path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
</svg>
</button>
<div class="w-10 h-10 rounded-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCFE0nJ7swkjKwjd11wwM-SezEwb10-pO1BAVcC-w6rh_bk8fsyVjcTF4jzYtVRKpcHtMr3utRoO5URHTFP2pZf0NRP29YmDPmKrcXEt_ru_zLYqFgUWw24_LhsmqI9kUjfDQEzz67D3qkWKHwFZI05HPlACVYMkhm_3bois0sNNjoOvnWLN3XVDryzk8FH1a5R6tuMx20akNSWbWroBiv9T2qnWhjg_clujkMzIlYjXVGrpf2W_kuv7HWYgLSpRfUk88KDNYg5_4R8");'></div>
</div>
</div>
</nav>
</header>
<main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
<div class="w-full max-w-md space-y-8 p-10 bg-white dark:bg-background-dark rounded-xl shadow-lg">
<div>
<h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Bienvenido a FitZone
          </h2>
<p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            Inicia Sesion para continuar.
          </p>
</div>
<form action="login.php" method="POST" class="mt-8 space-y-6">
    <?php if ($error): ?>
        <div class="mb-4 text-sm text-red-700 bg-red-100 p-2 rounded">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Campo email -->
    <div>
        <label for="email" class="sr-only">Email</label>
        <input id="email" name="email" type="email" required
               class="block w-full rounded-md border-gray-300 py-2 px-3"
               value="<?php echo htmlspecialchars($old_email); ?>">
    </div>

    <!-- Campo password -->
    <div>
        <label for="password" class="sr-only">Contraseña</label>
        <input id="password" name="password" type="password" required
               class="block w-full rounded-md border-gray-300 py-2 px-3">
    </div>

    <!-- Resto del formulario / botón -->
    <div class="flex items-center justify-between">
<div class="flex items-center">
<input class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary bg-background-light dark:bg-gray-800" id="remember-me" name="remember-me" type="checkbox"/>
<label class="ml-2 block text-sm text-gray-900 dark:text-gray-300" for="remember-me">Recuérdame</label>
</div>
<div class="text-sm">
<a class="font-medium text-primary hover:text-primary/80" href="#">Olvidaste Tu Contraseña?</a>
</div>
</div>
<div>
<button class="group relative flex w-full justify-center rounded-lg border border-transparent bg-primary py-3 px-4 text-sm font-bold text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" type="submit">
              Inicia Sesión
            </button>
</div>
<div class="text-sm text-center">
<a class="font-medium text-primary hover:text-primary/80" href="#">
              No Tienes Una Cuenta? Regístrate
            </a>
</div>
</form>
</div>
</main>
</div>

</body></html>

