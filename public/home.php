<?php
    $title = "DoctorKhujo: Home";
    require "template.php";
?>
<?php function body(){ ?>
        <div class="body">
            <h1>Welcome to DoctorKhujo</h1>

            <P>
                <?php
                    $user = $_SESSION['user'];
                    echo "Welcome, " . $user['name'] . "!<br><br>";
                    echo "Your details:<br>";
                    foreach($user as $key => $value){
                        echo $key . ": " . $value . "<br>";
                    }
                ?>
            </p>

        </div>
<?php } ?>
