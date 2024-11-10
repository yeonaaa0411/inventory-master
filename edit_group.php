<?php
$page_title = 'Edit Group';
require_once('includes/load.php');
page_require_level(1);

$e_group = find_by_id('user_groups', (int)$_GET['id']);
if (!$e_group) {
    $session->msg("d", "Missing Group id.");
    redirect('group.php');
}

if (isset($_POST['update'])) {
    $req_fields = array('group-name', 'status');
    validate_fields($req_fields);

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($_POST['group-level']));
        $status = remove_junk($db->escape($_POST['status']));

        // Only check for duplicate level if the level has changed
        if ($level != $e_group['group_level']) {
            $existing_group = find_by_groupLevel($level);
            if ($existing_group && $existing_group['id'] != $e_group['id']) {
                $session->msg('d', '<b>Sorry!</b> Entered Group Level already exists in the database!');
                redirect('edit_group.php?id=' . (int)$e_group['id'], false);
            }
        }

        // Update the group information
        $query  = "UPDATE user_groups SET ";
        $query .= "group_name='{$name}', group_level='{$level}', group_status='{$status}' ";
        $query .= "WHERE id='{$db->escape($e_group['id'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Group has been updated!");
            redirect('group.php', false);
        } else {
            $session->msg('d', 'Sorry, failed to update Group!');
            redirect('edit_group.php?id=' . (int)$e_group['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_group.php?id=' . (int)$e_group['id'], false);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .header-bg {
        background-color: #eaf5e9;
    }
  </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="mt-6 ml-6">
    <div class="w-2/6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-4 header-bg">
                <h2 class="text-3xl font-bold">EDIT GROUP</h2>
            </div>
            <div class="p-4">
                <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id']; ?>">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Group Name</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="level" class="block text-gray-700 text-sm font-bold mb-2">Group Level</label>
                        <?php if ((int)$e_group['group_level'] === 1 || (int)$e_group['group_level'] === 2): ?>
                            <input type="text" class="form-control border rounded w-full py-2 px-3" value="<?php echo (int)$e_group['group_level']; ?>" readonly>
                            <input type="hidden" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
                        <?php else: ?>
                            <select class="form-control border rounded w-full py-2 px-3" name="group-level" required>
                                <option value="1" <?php if ((int)$e_group['group_level'] === 1) echo 'selected'; ?>>1</option>
                                <option value="2" <?php if ((int)$e_group['group_level'] === 2) echo 'selected'; ?>>2</option>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="status" required>
                            <option value="1" <?php if ($e_group['group_status'] === '1') echo 'selected'; ?>>Active</option>
                            <option value="0" <?php if ($e_group['group_status'] === '0') echo 'selected'; ?>>Deactive</option>
                        </select>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
