<?php
$page_title = 'Add Group';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(1);
?>

<?php
if (isset($_POST['add'])) {
    $req_fields = array('group-name', 'group-level');
    validate_fields($req_fields);

    if (find_by_groupName($_POST['group-name']) === false) {
        $session->msg('d', '<b>Sorry!</b> Entered Group Name already in database!');
        redirect('add_group.php', false);
    } elseif (find_by_groupLevel($_POST['group-level']) === false) {
        $session->msg('d', '<b>Sorry!</b> Entered Group Level already in database!');
        redirect('add_group.php', false);
    }
    
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($_POST['group-level']));
        $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO user_groups (";
        $query .= "group_name, group_level, group_status";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$level}', '{$status}'";
        $query .= ")";
        
        if ($db->query($query)) {
            // success
            $session->msg('s', "Group has been created! ");
            redirect('add_group.php', false);
        } else {
            // failed
            $session->msg('d', ' Sorry failed to create Group!');
            redirect('add_group.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_group.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default" style="width: 400px; overflow: hidden;"> <!-- Set a specific width and hide overflow -->
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Add New Group</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_group.php">
                <div class="form-group">
                    <label for="name" class="control-label">Group Name</label>
                    <input type="text" class="form-control" name="group-name" placeholder="Group Name">
                </div>
                
                <div class="form-group">
                    <label for="level" class="control-label">Group Level</label>
                    <input type="number" class="form-control" name="group-level" placeholder="Group Level">
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status">
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
                    </select>
                </div>
                
                <div class="form-group clearfix">
                    <button type="submit" name="add" class="btn btn-info">Add Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
