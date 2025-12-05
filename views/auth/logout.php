<?php
session_start();
session_destroy(); 
header("Location: /DoAnTHWeb/views/auth/login.php");
exit();
?>
