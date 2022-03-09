<?php
    $title = "DoctorKhujo: Pending Medical Centers";
    require "template.php";

    include_once "../utils/utils.php";
    include_once "../utils/db_connector.php";
    include_once "../models/organization.php";
    
    $user = get_default($_SESSION, 'user');

    $conn = DBConnector::get_connection(get_config());
    
    if(isset($_POST['action']) && isset($_POST['id'])){
        $target_org = Organization::get_by_id($conn, $_POST['id']);
        if($target_org){
            if($_POST['action'] == 'Approve'){
                $target_org->status = 'approved';
            }else{
                $target_org->status = 'declined';
            }
            $target_org->update($user['id']);
            $org_creator = User::get_by_id($conn, $target_org->creator_id);
            if($target_org->status == 'approved' && $org_creator){
                $org_creator->role = 'organization_admin';
                $org_creator->update($user['id']);
            }
        }
    }
?>
<?php function body(){ ?>

    <div class="body">
        <!--<?php 
            include_once "../utils/utils.php";
            include_once "../utils/db_connector.php";
            include_once "../models/organization.php";
            
            $user = get_default($_SESSION, 'user');
        
            $conn = DBConnector::get_connection(get_config());
            $pending_orgs = Organization::get_multiple($conn, "status = 'pending'");
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
                    <th>Action</th>
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
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $org->get_id() ?>">
                                <input type="submit" name="action" value="Approve">
                                <input type="submit" name="action" value="Decline">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <?php if($pending_orgs){ ?>
            <h1>Pending Organizations</h1>
            <?php create_table($pending_orgs, true) ?>
        <?php } ?>
        
        <?php if(!$pending_orgs){ ?>
            <h3>No medical center to display</h3>
        <?php } ?>
    </div>
<?php } ?>

