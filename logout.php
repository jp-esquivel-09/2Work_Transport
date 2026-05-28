<?php
// logout.php - ESTO AL PRINCIPIO
session_start();
session_destroy();
header("Location: login.php?success=Ha cerrado sesión correctamente");
exit();
?>