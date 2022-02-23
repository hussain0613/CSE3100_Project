<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoctorKhujo: Home</title>
</head>
<body>
    <h1>Welcome to DoctorKhujo</h1>

    <P>
        <?php
            $script_dir = dirname(__FILE__);
            include_once $script_dir . "/../utils/auth.php";
            require_login();

            
            if(session_status() != 2) session_start();
            $user = $_SESSION['user'];
            echo "Welcome, " . $user['name'] . "!<br><br>";
            echo "Your details:<br>";
            foreach($user as $key => $value){
                echo $key . ": " . $value . "<br>";
            }
        ?>
    </p>

    <a href="logout.php">Logout</a>
    
</body>
</html>