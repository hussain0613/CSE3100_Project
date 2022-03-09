<?php
    $script_dir = dirname(__FILE__);
    include_once $script_dir . "/../utils/auth.php";
    require_login();

    
    if(session_status() != 2) session_start();
    $user = get_default($_SESSION, 'user');
    $role = get_default($user, 'role');
    if($role === 'admin'){
        $_SESSION['error_message'] = "A site admin can't create organizations.";
        header("Location: index.php");
        exit();
    }
?>

<?php
    if(isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["phone"])){
        require_once $script_dir."/../models/organization.php";
        require_once $script_dir."/../models/user.php";
        require_once $script_dir."/../utils/db_connector.php";
        require_once $script_dir."/../utils/utils.php";

        $name = $_POST["name"];
        $address = $_POST["address"];
        
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $website = $_POST["website"];

        $conn = DBConnector::get_connection(get_config());

        $organization = new Organization($conn);
        $organization->name = $name;
        $organization->address = $address;
        $organization->phone = $phone;
        $organization->email = $email;
        $organization->website = $website;
        $organization->status = 'pending';

        try{
            $organization->insert($user['id']);
        }catch(Exception $e){
            if(strpos($e->getMessage(), "Duplicate entry") !== false){
                $_SESSION['error_message'] = $e->getMessage();
            }
            else{
                $_SESSION['error_message'] = "Something went wrong. Please try again.";
            }
            header("Location: register_organization.php");
            exit();
        }
        

        $_SESSION["success_message"] = "Organization created successfully! Now wait for the admin to approve your request.";
        header("Location: index.php");
        exit();
    }else{
        $_SESSION["error_message"] = "Name, Address and Phone fields are required.";
        header("Location: register_organization.php");
        exit();
    }

?>