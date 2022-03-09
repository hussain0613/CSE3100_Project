<?php
    $title = "DoctorKhujo: Sessions";
    require "template.php";

    include_once "../utils/utils.php";
    include_once "../utils/db_connector.php";
    include_once "../models/organization.php";
    include_once "../models/doctor_session.php";

    
    $user = get_default($_SESSION, 'user');

    $conn = DBConnector::get_connection(get_config());
    
    if(isset($_POST['action']) && isset($_POST['id'])){
        try{
            $target_session = DoctorSession::get_by_id($conn, $_POST['id']);
        
            if($target_session){
                $target_session->delete();
                $_SESSION['success_message'] = "Session deleted successfully";
                header("Location: view_sessions.php");
                exit();
            }
        }catch(Error|Exception $e){
            // echo $e->getMessage();
        }
    }
?>
<?php function body(){ ?>

    <div class="body">
        <?php 
            include_once "../utils/utils.php";
            include_once "../utils/db_connector.php";
            include_once "../models/organization.php";
            include_once "../models/doctor_session.php";
            
            $user = get_default($_SESSION, 'user');
        
            $conn = DBConnector::get_connection(get_config());


            $sql = "SELECT s.id, o.name, doctors_name, specialization, bmdc_reg_no, time_slot, day, fee, seat
                    FROM `" . DoctorSession::$__tablename__ . "`s
                    join `" . Organization::$__tablename__ . "` o on s.organization_id = o.id
                    where s.creator_id = " . $user['id'] . ";";
                
            $result = $conn->query($sql);
            $result = $result->fetch_all(MYSQLI_ASSOC);

        ?>
        <?php function create_table($sessions){ ?>
            <table class="table">
                <tr>
                    <th>Organization</th>
                    <th>Doctor's Name</th>
                    <th>Specialization</th>
                    <th>BMDC Reg. No.</th>
                    <th>Time Slot</th>
                    <th>Day</th>
                    <th>Fee</th>
                    <th>Seat</th>
                    <th>Action</th>
                </tr>
                <?php foreach($sessions as $session){ ?>
                    <tr>
                        <td><?php echo get_default($session, "name") ?></td>
                        <td><?php echo get_default($session, "doctors_name") ?></td>
                        <td><?php echo get_default($session, "specialization") ?></td>
                        <td><?php echo get_default($session, "bmdc_reg_no") ?></td>
                        <td><?php echo get_default($session, "day") ?></td>
                        <td><?php echo get_default($session, "time_slot") ?></td>
                        <td><?php echo get_default($session, "fee") ?></td>
                        <td><?php echo get_default($session, "seat") ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo get_default($session, "id") ?>">
                                <input type="submit" name="action" value="Remove">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <?php if($result){ ?>
            <h1>Sessions</h1>
            <?php create_table($result, true) ?>
        <?php } ?>
        
        <?php if(!$result){ ?>
            <h3>No sessions to display</h3>
        <?php } ?>
    </div>
<?php } ?>

