<?php
    $title = "DoctorKhujo: Create Session";
    include "template.php";
?>

<?php function body(){ ?>
    <div class="body">
        <form method="POST" action="create_session_controller.php">
            <select name="organization_id">
                <?php
                    include_once "../utils/utils.php";
                    include_once "../utils/db_connector.php";
                    include_once "../models/organization.php";
                
                    $user = get_default($_SESSION, 'user');
                
                    $conn = DBConnector::get_connection(get_config());
                    $users_orgs = Organization::get_multiple($conn, "creator_id = " . $user['id'] . " and status = 'approved'");

                    foreach($users_orgs as $organization){
                        echo "<option value=\"" . $organization->get_id() . "\">" . $organization->name . "</option>";
                    }
                ?>
            </select>
            <br>

            <input type="text" name="doctors_name" placeholder="Doctor's Name" required> <br>
            <input type="text" name="specialization" placeholder="Specialization" required> <br>
            <input type="text" name="bmdc_reg_no" placeholder="BMDC Reg. No." required><br>
            <input type="time" name="time_slot" placeholder="Time Slot" required><br>
            
            <select name="day" required>
                <option value="monday">Monday</option>
                <option value="tuesday">Tuesday</option>
                <option value="wednesday">Wednesday</option>
                <option value="thursday">Thursday</option>
                <option value="friday">Friday</option>
                <option value="saturday">Saturday</option>
                <option value="sunday">Sunday</option>
            </select>
            <br>

            <input type="integer" name="fee" placeholder="Fee" required><br>
            <input type="integer" name="seat" placeholder="Seat" required><br>
            

            <input type="submit" value="Create Session">
        </form>

    </div>
<?php } ?>
