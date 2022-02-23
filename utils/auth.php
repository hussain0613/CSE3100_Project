<?php
$script_dir = dirname(__FILE__);
require_once $script_dir."/utils.php";
require_once $script_dir."/db_connector.php";
require_once $script_dir."/../models/user.php";

function login(mysqli $conn): array | null{
    if(session_status() != 2) session_start();
    if(!isset($_POST["username"]) || !isset($_POST['password'])){
        return null;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $remember_me = get_default($_POST, 'remember_me', false);

    try{
        $user = User::get_by_username($conn, $username);
    }catch(TypeError $e){
        return null;
    }
    
    if($user){
        if($user->verify_password($password)){
            $_SESSION['user'] = $user->get_map();
            
            if($remember_me){
                $config = get_config();
                setcookie("token", generate_auth_token($user, $config["secret_key"]), time() + (86400 * 30), "/");
            }
            
            return $_SESSION['user'];
        }
    }
    return null;
}


function generate_auth_token(User $user, string $secret_key): string{
    $token = $user->get_id() . ":" . $user->get_uid() . ":" . password_hash($secret_key, PASSWORD_DEFAULT);
    return $token;
}


function verify_auth_token(mysqli $conn, string $token, string $secret_key): array | null{
    $parts = explode(":", $token);
    if(count($parts) != 3){
        return null;
    }
    $user_id = $parts[0];
    $uid = $parts[1];
    $hash = $parts[2];

    if(!password_verify($secret_key, $hash)){
        return null;
    }
    
    $user = User::get_by_id($conn, $user_id);
    if($user){
        if($user->get_uid() == $uid){
            return $user->get_map();
        }
    }
    return null;
}


function get_current_app_user(mysqli $conn): array | null{
    if(session_status() != 2) session_start();
    if(!isset($_SESSION['user'])){
        if(isset($_COOKIE['token'])){
            $token = $_COOKIE['token'];
            $user = verify_auth_token($conn, $token, get_config()["secret_key"]);
            if($user){
                $_SESSION['user'] = $user;
                return $user;
            }
        }
    }else{
        $user = $_SESSION['user'];
        return $user;
    }

    return null;
}


function require_login(){
    if(session_status() != 2) session_start();

    $conn = DBConnector::get_connection(get_config());
    $user = get_current_app_user($conn);
    $conn->close();
    
    if (!$user) {
        $_SESSION["error_message"] = "Please Login to continue.";
        header("Location: auth.php?next=".$_SERVER['REQUEST_URI']);
        exit();
    }
}

?>