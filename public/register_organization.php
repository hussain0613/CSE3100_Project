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

            <input type="submit" value="Create Organization">
        </form>

    </div>
<?php } ?>
