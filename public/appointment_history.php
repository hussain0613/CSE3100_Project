<?php
    $title = "DoctorKhujo: Sessions";
    require "template.php";

    include_once "../utils/utils.php";
    include_once "../utils/db_connector.php";
    include_once "../models/organization.php";
    include_once "../models/doctor_session.php";

    
    $user = get_default($_SESSION, 'user');
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


            $sql = "SELECT o.name, doctors_name, specialization, bmdc_reg_no, time_slot, day, fee, a.appointment_date, o.address
                    from `appointment` a
                    join `doctor_session` s on a.doctor_session_id = s.id
                    join `organization` o on s.organization_id = o.id
                    where a.creator_id = " . $user['id'] . ";";
                
            $result = $conn->query($sql);
            $result = $result->fetch_all(MYSQLI_ASSOC);


            $sql = "SELECT o.name, doctors_name, specialization, bmdc_reg_no, time_slot, day, fee, a.appointment_date, u.username
                    from `appointment` a
                    join `doctor_session` s on a.doctor_session_id = s.id
                    join `organization` o on s.organization_id = o.id
                    join `user` u on a.creator_id = u.id
                    where o.creator_id = " . $user['id'] . ";";
                
            $result2 = $conn->query($sql);
            $result2 = $result2->fetch_all(MYSQLI_ASSOC);

        ?>
        <?php function create_table($sessions, $flag = true){ ?>
            <table class="table">
                <tr>
                    <th>Organization</th>
                    <th> <?php echo $flag? "Address" : "Patient's Name" ?> </th>
                    <th>Doctor's Name</th>
                    <th>Specialization</th>
                    <th>BMDC Reg. No.</th>
                    <th>Day</th>
                    <th>Time Slot</th>
                    <th>Fee</th>
                    <th>Date</th>
                </tr>
                <?php foreach($sessions as $session){ ?>
                    <tr>
                        <td><?php echo get_default($session, "name") ?></td>
                        <td><?php echo get_default($session, $flag? "address" : "username") ?></td>
                        <td><?php echo get_default($session, "doctors_name") ?></td>
                        <td><?php echo get_default($session, "specialization") ?></td>
                        <td><?php echo get_default($session, "bmdc_reg_no") ?></td>
                        <td><?php echo get_default($session, "day") ?></td>
                        <td><?php echo get_default($session, "time_slot") ?></td>
                        <td><?php echo get_default($session, "fee") ?></td>
                        <td><?php echo get_default($session, "appointment_date") ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <?php if($result){ ?>
            <h1>Your Appointments</h1>
            <?php create_table($result, true) ?>
        <?php } ?>

        <?php if($result2){ ?>
            <h1>Your Organizations' Appointments</h1>
            <?php create_table($result2, false) ?>
        <?php } ?>
        
        <?php if(!$result){ ?>
            <h3>No Appointments to display</h3>
        <?php } ?>
    </div>
<?php } ?>

