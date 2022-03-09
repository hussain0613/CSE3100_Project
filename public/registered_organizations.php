<?php
    $title = "DoctorKhujo: Medical Centers";
    require "template.php";
?>
<?php function body(){ ?>

    <div class="body">
        <!--<?php 
            include_once "../utils/utils.php";
            include_once "../utils/db_connector.php";
            include_once "../models/organization.php";

            $user = get_default($_SESSION, 'user');

            $conn = DBConnector::get_connection(get_config());
            $users_orgs = Organization::get_multiple($conn, "creator_id = " . $user['id']);
            $other_orgs = Organization::get_multiple($conn, "creator_id != " . $user['id'] . " and status = 'approved'");
        ?> -->
        <?php function create_table($orgs, $display_status = false){ ?>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Website</th>
                    <?php if($display_status){ ?>
                            <th>Status</th>
                    <?php } ?>
                </tr>
                <?php foreach($orgs as $org){ ?>
                    <tr>
                        <td><?php echo $org->name ?></td>
                        <td><?php echo $org->address ?></td>
                        <td><?php echo $org->phone ?></td>
                        <td><?php echo $org->email ?></td>
                        <td><?php echo get_default2($org, "website", "-") ?></td>
                        <?php if($display_status){ ?>
                            <td><?php echo $org->status ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <?php if($users_orgs){ ?>
            <h1>Your Organizations</h1>
            <?php create_table($users_orgs, true) ?>
        <?php } ?>
        
        <?php if($other_orgs){ ?>
            <h1>Organizations</h1>
            <?php create_table($other_orgs) ?>
        <?php } ?>

        <?php if(!$users_orgs && !$other_orgs){ ?>
            <h3>No medical center to display</h3>
        <?php } ?>
    </div>
<?php } ?>
