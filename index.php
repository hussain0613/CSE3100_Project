<?php
if(session_status() != 2) session_start();

$script_dir = dirname(__FILE__);
require_once $script_dir."/utils/utils.php";
require_once $script_dir."/utils/db_connector.php";
require_once $script_dir."/utils/auth.php";

$conn = DBConnector::get_connection(get_config());
$user = get_current_app_user($conn);
$conn->close();
if($user){
    header("Location: public/home.php");
    exit();
}else{
    header("Location: public/index.html");
    exit();
}
?>
