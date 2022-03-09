<?php
    require_once "../utils/utils.php";
    require_once "../utils/db_connector.php";
    require_once "../models/doctor_session.php";
    require_once "../models/appointment.php";

    if(session_status () != 2) session_start();
    
    if(isset($_POST['action']) && isset($_POST['id'])){
        $conn = DBConnector::get_connection(get_config());

        $doc_session = DoctorSession::get_by_id($conn, $_POST['id']);
        $booked_seats = Appointment::get_count_by_date_n_session_id($conn, $_POST['date'], $_POST['id']);

        if($doc_session->seat <= $booked_seats){
            $_SESSION['error_message'] = "No seats available";
            header("Location: create_appointment.php?specialization=" . $_POST['specialization']);
            exit();
        }

        $apt = new Appointment($conn);
        $apt->doctor_session_id = $_POST['id'];
        $apt->appointment_date = $_POST['date'];
        
        
        $user = $_SESSION['user'];
        try{
            $apt->insert($user['id']);
            $_SESSION['success_message'] = "Appointment created successfully";
            header("Location: create_appointment.php");
            exit();
        }catch(Exception|Error $e){
            if(strpos($e->getMessage(), "Duplicate entry") > 0){
                $_SESSION['error_message'] = "You have already booked for this session for this date.";
            }
            else{
                $_SESSION['error_message'] = "Something went wrong. Please try again.";
            }
            header("Location: create_appointment.php?specialization=" . $_POST['specialization']);
            exit();
        }
    }
?>

<?php
    $title = "DoctorKhujo: Create Appointment";
    include "template.php";
?>

<?php function body(){ ?>
    <div class="body">
        <?php
            require_once "../utils/utils.php";
            require_once "../utils/db_connector.php";
            require_once "../models/appointment.php";

            $user = $_SESSION['user'];
            $conn = DBConnector::get_connection(get_config());

            $sql = "SELECT DISTINCT specialization FROM doctor_session;";
            $result = $conn->query($sql);
            $result = $result->fetch_all(MYSQLI_ASSOC);
        ?>
        <form>
            <label>Specialization: </label>
            <select name="specialization" class = "form-select" required>
                <?php foreach($result as $row){ ?>
                    <option value="<?php echo $row['specialization']; ?>"><?php echo $row['specialization']; ?></option>
                <?php } ?>
            </select>
            <!-- <input type="date" name="date" required> -->
            <input type="submit" value="Search">
        </form>
        
        <?php function create_table($sessions){ ?>
            <table class="table">
                <tr>
                    <th>Organization</th>
                    <th>Doctor's Name</th>
                    <th>Specialization</th>
                    <th>BMDC Reg. No.</th>
                    <th>Day</th>
                    <th>Time Slot</th>
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
                                <input type="number" name="id" value="<?php echo get_default($session, "id") ?>" hidden>
                                <input type="text" name="specialization" value="<?php echo get_default($session, "specialization") ?>" hidden>
                                <input type="date" name="date" required>
                                <input type="submit" name="action" value="Book">
                            </form>        
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <?php
            if(isset($_GET['specialization']) && $_GET['specialization'] != ""){
                $specialization = $_GET['specialization'];
                
                $sql = "SELECT s.id, o.name, doctors_name, specialization, bmdc_reg_no, time_slot, day, fee, seat
                        FROM doctor_session s
                        join `organization` o on s.organization_id = o.id
                        where o.status = 'approved' and specialization = '" . $specialization . "';";
                $result = $conn->query($sql);
                if($result){ $result = $result->fetch_all(MYSQLI_ASSOC);
                
                    echo "<h3>Available sessions for '$specialization'</h3>";
                    create_table($result);
                }else{
                    echo "<h3>No sessions available</h3>";
                }
            }
        ?>

    </div>
<?php } ?>
