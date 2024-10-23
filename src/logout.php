<?php
session_start();
session_unset();  
session_destroy(); 
header("Location: Verify/login.php"); 
exit();
?>