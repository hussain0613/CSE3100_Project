<?php
    $script_dir = dirname(__FILE__);
    include_once $script_dir . "/../utils/auth.php";
    require_login();

    
    if(session_status() != 2) session_start();
    $user = get_default($_SESSION, 'user');
?>

<?php
    if(isset($_POST["organization_id"]) && isset($_POST["doctors_name"]) && isset($_POST["specialization"]) && isset($_POST["bmdc_reg_no"]) && isset($_POST["time_slot"]) && isset($_POST["fee"]) && isset($_POST["day"]) ){
        require_once $script_dir."/../models/doctor_session.php";
        require_once $script_dir."/../models/user.php";
        require_once $script_dir."/../utils/db_connector.php";
        require_once $script_dir."/../utils/utils.php";

        try{
            $conn = DBConnector::get_connection(get_config());
            
            $session = new DoctorSession($conn);
            $session->organization_id = $_POST["organization_id"];
            $session->doctors_name = $_POST["doctors_name"];
            $session->specialization = $_POST["specialization"];
            $session->bmdc_reg_no = $_POST["bmdc_reg_no"];
            $session->time_slot = $_POST["time_slot"];
            $session->fee = $_POST["fee"];
            $session->day = $_POST["day"];
            $session->seat = $_POST["seat"];

            $session->insert($user['id']);
            
            $_SESSION["success_message"] = "Doctor Session created successfully!";
            header("Location: create_session.php");
            exit();
        }catch(Exception $e){
            if(strpos($e->getMessage(), "Duplicate entry") !== false){
                $_SESSION['error_message'] = "Given doctor's Session already exists for the given day and time.";
            }
            else{
                $_SESSION['error_message'] = "Something went wrong. Please try again.";
            }
            header("Location: create_session.php");
            exit();
        }
        
    }else{
        $_SESSION["error_message"] = "All the fields are required.";
        header("Location: register_organization.php");
        exit();
    }

?>