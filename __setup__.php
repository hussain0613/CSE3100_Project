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

echo "Not going to do anything yet.";
exit();

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
}


$conn = DBConnector::get_connection(get_config());

// creating all tables
function create_all_tables($conn){
    User::create_table($conn);
}

function delete_all_tables($conn){
    User::delete_table($conn);
}

// delete_all_tables($conn);
// create_all_tables($conn);
$conn->close()

?>

<form>
    <input type="checkbox" name="choices" value="config"> Reset Config File<br>
    <input type="checkbox" name="choices" value="tables"> Reset Tables<br>
    <input type="submit" value="Submit">
</form>
    
</body>
</html>
