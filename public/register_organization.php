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
    $title = "DoctorKhujo: Medical Center Registration";
    require "template.php";
?>
<?php function body(){ ?>
    <div class="body">
        <form method="POST" action="register_organization_controller.php">
            <input type="text" name="name" placeholder="Name" required> <br>
            <textarea name="address" placeholder="Address"></textarea> <br>

            <input type="phone" name="phone" placeholder="Phone" required> <br>
            <input type="email" name="email" placeholder="Email"> <br>
            <input type="text" name="website" placeholder="Website"> <br>

            <input type="submit" value="Register Organization">
        </form>

    </div>
<?php } ?>
