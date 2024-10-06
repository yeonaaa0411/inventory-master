<?php
$page_title = 'Edit Group';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(1);
?>

<?php
$e_group = find_by_id('user_groups', (int)$_GET['id']);
if (!$e_group) {
    $session->msg("d", "Missing Group id.");
    redirect('group.php');
}
?>

<?php
if (isset($_POST['update'])) {
    $req_fields = array('group-name', 'group-level');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($_POST['group-level']));
        $status = remove_junk($db->escape($_POST['status']));

        $query  = "UPDATE user_groups SET ";
        $query .= "group_name='{$name}', group_level='{$level}', group_status='{$status}' ";
        $query .= "WHERE ID='{$db->escape($e_group['id'])}'";
        $result = $db->query($query);
        
        if ($result && $db->affected_rows() === 1) {
            // success
            $session->msg('s', "Group has been updated! ");
            redirect('edit_group.php?id=' . (int)$e_group['id'], false);
        } else {
            // failed
            $session->msg('d', ' Sorry failed to update Group!');
            redirect('edit_group.php?id=' . (int)$e_group['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_group.php?id=' . (int)$e_group['id'], false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default" style="width: 400px; overflow: hidden;"> <!-- Set a specific width and hide overflow -->
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-pencil"></span>
                <span>Edit Group</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id']; ?>">
                <div class="form-group">
                    <label for="name" class="control-label">Group Name</label>
                    <input type="text" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
                </div>

                <div class="form-group">
                    <label for="level" class="control-label">Group Level</label>
                    <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status">
                        <option <?php if ($e_group['group_status'] === '1') echo 'selected="selected"'; ?> value="1">Active</option>
                        <option <?php if ($e_group['group_status'] === '0') echo 'selected="selected"'; ?> value="0">Deactive</option>
                    </select>
                </div>

                <div class="form-group clearfix">
                    <button type="submit" name="update" class="btn btn-info">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
