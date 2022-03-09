
<?php
    $script_dir = dirname(__FILE__);
    include_once $script_dir . "/../utils/auth.php";
    include_once $script_dir . "/../utils/utils.php";
    require_login();

    if(session_status() != 2) session_start();
    $user = get_default($_SESSION, 'user');
?>

<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .custom_container{
            margin: 2%;
            width: 96%;
        }
    </style>
    <title><?php echo $title ?></title>
</head>
<body>
    <div class="custom_container">
        <div id = "navbar justify-content-between">
            <span id = "menu_left_side">
                <a class="navbar-brand" href="home.php" class="btn btn-outline-secondary">DoctorKhujo</a>
                <a href="appointment_history.php" class="btn btn-outline-secondary">Appointment History</a>
                <a href="create_appointment.php" class="btn btn-outline-secondary">Make Appointment</a>
            </span>
            
            <span id="menu_right_side" class="float-end me-5">
                <?php if(get_default($user, "role") == "admin"){ ?>
                    <a href="pending_organizations.php" class="btn btn-outline-secondary">Pending Organizations</a>
                <?php }else if (get_default($user, "role") == "organization_admin"){ ?>
                    <a href="create_session.php" class="btn btn-outline-secondary">Create Session</a>
                    <a href="view_sessions.php" class="btn btn-outline-secondary">View Sessions</a>
                <?php } ?>
                
                <a href="registered_organizations.php" class="btn btn-outline-secondary">Registered Medical Centers</a>
                <a href="register_organization.php" class="btn btn-outline-secondary">Register Your Medical Center</a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </span>
        </div>
        <br>
        <hr>

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
            ?>
        </div>

        <?php body(); ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

</body>
</html>

        