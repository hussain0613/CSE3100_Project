<?php
    $client_ip = $_SERVER['REMOTE_ADDR'];
    if(!hash_equals($client_ip, "127.0.0.1")){
        header("HTTP/1.1 403 Forbidden");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
</head>
<body>

<?php
include_once "utils/utils.php";
include_once "utils/db_utils.php";
include_once "utils/db_connector.php";
include_once "utils/config.php";
include_once "models/user.php";
include_once "models/organization.php";
include_once "models/doctor_session.php";
include_once "models/appointment.php";


// reading configuration
try{
    $config = get_config(true);
}catch(Exception $e){
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Please check the config file and try again. Or you may create a new config file.<br>";
}

function create_config_file(){
    $config = [
        "secret_key" => "change_this_dummySecretKey",
        "db_host" => "localhost",
        "db_port" => 0, // to use default port
        "db_user" => "root",
        "db_password" => "",
        "db_name" => "demo_db"
    ];
    $GLOBALS["config"] = $config;

    // write the config file
    $fn = "server_settings.json";
    $config_file = fopen($fn, "w") or throw new Exception("[!] Error writing to config file: $fn");
    fwrite($config_file, json_encode($config));
    fclose($config_file);

    echo "Config file created successfully with default values. Please modify them as necessary.<br>";
}


function create_all_tables($conn){
    User::create_table($conn);
    Organization::create_table($conn);
    DoctorSession::create_table($conn);
    Appointment::create_table($conn);
}

function delete_all_tables($conn){
    Appointment::delete_table($conn);
    DoctorSession::delete_table($conn);
    Organization::delete_table($conn);
    User::delete_table($conn);
}

?>

<h3>Actions:</h3>
<form method="POST">
    <input type="checkbox" name="choices[]" value="create_config"> Create Config File<br>
    <input type="checkbox" name="choices[]" value="create_tables"> Create Tables<br>
    <input type="checkbox" name="choices[]" value="delete_tables"> Delete Tables<br>
    <input type="submit" value="Submit">
</form>


<?php
    $method = $_SERVER["REQUEST_METHOD"] = "POST";
    if($method == "POST"){
        if(isset($_POST["choices"])){
            $choices = $_POST["choices"];
            if(count($choices) == 0){
                echo "[!] No action chosen.<br>";
            }
            else{
                $conn = DBConnector::get_connection(get_config());
                if(in_array("create_config", $choices)){
                    create_config_file();
                }
                if(in_array("create_tables", $choices)){
                    create_all_tables($conn);
                }
                if(in_array("delete_tables", $choices)){
                    delete_all_tables($conn);
                }
    
                $conn->close();
            }
        }
        else{
            echo "[!] No action chosen.<br>";
        }    
    }
    
?>
</body>
</html>
