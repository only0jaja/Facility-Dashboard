<?php 
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page and exit
header("Location: login.php");
exit();
?>