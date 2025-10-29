<?php
session_start();
session_destroy();
header("Location: ../login_de_usuario/code.php");
exit();
?>