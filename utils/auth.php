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
    $payload = [
        "username" => $user->username,
        "scope" => "login",
        "exp" => time() + (86400 * 30)
    ];
    
    $token = encode_jwt($payload, $secret_key . $user->get_uid());
    return $token;
}


function verify_auth_token(mysqli $conn, string $token, string $secret_key): array | null{
    $parts = explode(".", $token);
    if(count($parts) != 3){
        return null;
    }

    try{
        $payload = json_decode(base64_decode($parts[1]), true);
    }catch(Exception $e){
        return null;
    }
    
    if(!$payload || !isset($payload["scope"]) || !isset($payload["username"])){
        return null;
    }
    
    if($payload["scope"] != "login"){
        return null;
    }

    try{
        $user = User::get_by_username($conn, $payload["username"]);
    }catch(TypeError $e){
        return null;
    }
    

    try{
        $payload = decode_jwt($token, $secret_key . $user->get_uid());
    }catch(Exception $e){
        throw $e;
        return null;
    }
    
    return $user->get_map();
}

function encode_jwt(array $payload, string $secret_key): string{
    $header = ['alg' => 'HS256','typ' => 'JWT'];
    $header = base64_encode(json_encode($header));
    $header = str_replace(['+', '/', '='], ['-', '_', ''], $header);
    
    $payload = base64_encode(json_encode($payload));
    $payload = str_replace(['+', '/', '='], ['-', '_', ''], $payload);
    
    $signature = base64_encode(hash_hmac('sha256', $header . "." . $payload, $secret_key, true));
    $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);
    
    $jwt = $header . "." . $payload . "." . $signature;
    
    return $jwt;
}


function decode_jwt(string $jwt, string $secret_key): array{
    $parts = explode(".", $jwt);
    if(count($parts) != 3){
        throw new LengthException("Invalid JWT");
    }
    
    $header = json_decode(base64_decode($parts[0]), true);
    $payload = json_decode(base64_decode($parts[1]), true);
    $signature = $parts[2];

    if(!isset($header['alg']) || $header['alg'] != 'HS256'){
        throw new InvalidArgumentException("Invalid algorithm");
    }

    if(!isset($header['typ']) || $header['typ'] != 'JWT'){
        throw new InvalidArgumentException("Invalid type");
    }

    if(isset($payload["exp"]) && $payload["exp"] < time()){
        throw new RuntimeException("Expired");
    }
    if(isset($payload["nbf"]) && $payload["nbf"] > time()){
        throw new RuntimeException("Not yet valid");
    }
    
    $expected_signature = base64_encode(hash_hmac('sha256', $parts[0] . "." . $parts[1], $secret_key, true));
    $expected_signature = str_replace(['+', '/', '='], ['-', '_', ''], $expected_signature);
    if(!hash_equals($expected_signature, $signature)){
        throw new RuntimeException("Invalid signature");
    }

    
    return $payload;
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