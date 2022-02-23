<?php
if(session_status() != 2) session_start();
$redirection_url = "auth.php";
if(isset($_REQUEST['next'])){
    $redirection_url = "auth.php?next=" . $_REQUEST['next'];
}

if(isset($_POST["form-name"])){
    $script_dir = dirname(__FILE__);
    require_once $script_dir."/../utils/auth.php";
    require_once $script_dir."/../utils/utils.php";
    require_once $script_dir."/../utils/db_connector.php";

    require_once $script_dir."/../models/user.php";
    
    $form_name = $_POST["form-name"];

    if(!strcmp($form_name, "register-form")){
        if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST["email"])){
            if(!strcmp($_POST['password'], $_POST['confirm_password'])){
                
                unset($_POST['confirm_password']);
                unset($_POST['form-name']);

                $user = null;
                try{
                    $conn = DBConnector::get_connection(get_config());
                    $user = User::create_user_from_assoc($conn, $_POST, 0);
                    $conn->close();
                }catch(Exception $e){
                    throw $e;
                }

                if($user){
                    $_SESSION['user'] = $user;
                    $_SESSION['success_message'] = "User account created successfully. Please login.";
                    header("Location: $redirection_url");
                    exit();
                }else{
                    $_SESSION['error_message'] = "Something went wrong. Please try again.";
                    header("Location: $redirection_url");
                    exit();
                }
                    
            }else{
                $_SESSION['error_message'] = "Passwords do not match";
                header("Location: $redirection_url");
                exit();
            }
        }else{
            $_SESSION['error_message'] = "username, email, password and confirm_password fields are required for registration";
            header("Location: $redirection_url");
            exit();
        }
    }
    else if(!strcmp($form_name, "login-form")){
        $conn = DBConnector::get_connection(get_config());

        if(isset($_POST['username']) && isset($_POST['password'])){
            $user = login($conn);
            
            $conn->close();
            if($user){
                if(isset($_REQUEST["next"])) $redirection_url = $_REQUEST["next"];
                else $redirection_url = "home.php";
                header("Location: $redirection_url");
                exit();
            }else{
                $_SESSION['error_message'] = "Invalid username or password";
                header("Location: $redirection_url");
                exit();
            }
        }else{
            $_SESSION["error_message"] = "username and password fields are required for login.";
            header("Location: $redirection_url");
            exit();
        }
    }
    else{
        $_SESSION["error_message"] = "Unknown form name: " . $form_name;
        header("Location: $redirection_url");
        exit();
    }
}

?>
