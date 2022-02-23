<?php
if(session_status() != 2) session_start();

$script_dir = dirname(__FILE__);
require_once $script_dir."/../utils/auth.php";

setcookie("token", "", time() - 3600, "/");
unset($_SESSION['user']);
header("Location: auth.php");
exit();
?>