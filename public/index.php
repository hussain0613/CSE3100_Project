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
                    echo "<h5>Welcome, " . $user['name'] . "!</h5>";
                    foreach($user as $key => $value){
                        echo "<p><b>" . ucwords($key) . "</b>: " . $value . "</p>";
                    }
                ?>
            </p>

        </div>
<?php } ?>
