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

    // Check if the group name already exists
    if (find_by_groupName($_POST['group-name']) === false) {
        $session->msg('d', '<b>Sorry!</b> Entered Group Name already in database!');
        redirect('add_group.php', false);
    }

    // Check if the group level already exists
    elseif (find_by_groupLevel($_POST['group-level']) === false) {
        $session->msg('d', '<b>Sorry!</b> Entered Group Level already in database!');
        redirect('add_group.php', false);
    }
    
    // If no errors, proceed to add the new group
    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($_POST['group-level']));
        $status = remove_junk($db->escape($_POST['status']));

        // Check for duplicate group level before inserting
        $query_check = "SELECT * FROM user_groups WHERE group_level = '{$level}' LIMIT 1";
        $result = $db->query($query_check);
        
        if ($db->num_rows($result) > 0) {
            $session->msg('d', '<b>Sorry!</b> Group Level already exists. Please choose a different level.');
            redirect('add_group.php', false);
        } else {
            $query  = "INSERT INTO user_groups (";
            $query .= "group_name, group_level, group_status";
            $query .= ") VALUES (";
            $query .= " '{$name}', '{$level}', '{$status}'";
            $query .= ")";
            
            if ($db->query($query)) {
                // success
                $session->msg('s', "Group has been created! ");
                redirect('group.php', false); // Redirect to group.php on success
            } else {
                // failed
                $session->msg('d', ' Sorry failed to create Group!');
                redirect('add_group.php', false);
            }
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_group.php', false);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    .header-bg {
        background-color: #eaf5e9; /* Light green color */
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
                <h2 class="text-3xl font-bold">
                    <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
                    ADD NEW GROUP
                </h2>
            </div>
            <div class="p-4">
                <form method="post" action="add_group.php">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Group Name</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="group-name" placeholder="Group Name" required>
                    </div>

                    <div class="mb-4">
                        <label for="level" class="block text-gray-700 text-sm font-bold mb-2">Group Level</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="group-level" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="status">
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="add" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Add Group
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
