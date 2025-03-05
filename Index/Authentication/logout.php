<?php
session_start();
session_unset(); 
session_destroy(); // Destroy the session data


header('Location: login.php');
exit();
?>
