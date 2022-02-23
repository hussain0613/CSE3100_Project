<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoctorKhujo: Welcome</title>
</head>
<body>
    <h1>DoctorKhujo: Welcome</h1>
    
    <div id = "messages_div">
        <?php
            if(session_status() != 2) session_start();
            if(isset($_SESSION['error_message'])){
                echo "<p class=\"error_message\">" . $_SESSION["error_message"] . "</p>";
                unset($_SESSION["error_message"]);
            }

            if(isset($_SESSION['success_message'])){
                echo "<p class=\"success_message\">" . $_SESSION["success_message"] . "</p>";
                unset($_SESSION["success_message"]);
            }

            if(isset($_REQUEST['next'])){
                $controller_url = "auth_form_controller.php?next=" . $_REQUEST['next'];
            }
            else{
                $controller_url = "auth_form_controller.php";
            }
        ?>
    </div>

    <h1> Login </h1>
    <form id="login-form" method="POST" action=<?php echo $controller_url ?>>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="checkbox" name="remember_me" value="true"> Remeber Me<br>

        <input type="text" name="form-name" value="login-form" hidden>

        <input type="submit" value="Login">
    </form>

    <h1> Register </h1>
    <form id="register-form" method="POST" action=<?php echo $controller_url ?>>
        <input type="text" name="name" placeholder="Name"><br>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder = "Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        
        <input type="text" name="form-name" value="register-form" hidden>
        
        <input type="submit" value="Register">
    </form>
</body>
</html>