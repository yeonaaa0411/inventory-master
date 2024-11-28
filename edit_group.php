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
    $req_fields = array('group-name');
    validate_fields($req_fields);

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($_POST['group-level']));

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
        $query .= "group_name='{$name}', group_level='{$level}' ";
        $query .= "WHERE id='{$db->escape($e_group['id'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Group has been updated!");
            redirect('group.php', false);
        } else {
            $session->msg('d', 'Update failed. No changes were made to the group.');
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
        background-color: #d1fae5; /* bg-green-50 */
    }

    /* Card Styling */
    .card {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    /* Button Styling */
    .btn-primary {
        background-color: #4CAF50;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    /* Form Input Styling */
    .form-input {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        width: 100%;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #4CAF50;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
  </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="mt-6 ml-6">
    <div class="w-full sm:w-2/3 lg:w-1/3">
        <div class="card">
            <div class="flex justify-between items-center p-4 header-bg">
                <h2 class="text-3xl font-bold">Edit Group</h2>
            </div>
            <div class="p-6">
                <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id']; ?>">

                    <div class="mb-4">
                        <label for="name" class="form-label">Group Name</label>
                        <input type="text" class="form-input" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="level" class="form-label">Group Level</label>
                        <?php if ((int)$e_group['group_level'] === 1 || (int)$e_group['group_level'] === 2): ?>
                            <input type="text" class="form-input" value="<?php echo (int)$e_group['group_level']; ?>" readonly>
                            <input type="hidden" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
                        <?php else: ?>
                            <select class="form-input" name="group-level" required>
                                <option value="1" <?php echo ($e_group['group_level'] == 1) ? 'selected' : ''; ?>>Level 1</option>
                                <option value="2" <?php echo ($e_group['group_level'] == 2) ? 'selected' : ''; ?>>Level 2</option>
                                <option value="3" <?php echo ($e_group['group_level'] == 3) ? 'selected' : ''; ?>>Level 3</option>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="update" class="btn-primary">Update Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

</body>
</html>
