<?php
$page_title = 'Edit User';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(1);
?>

<?php
$e_user = find_by_id('users', (int)$_GET['id']);
$groups  = find_all('user_groups');
if (!$e_user) {
    $session->msg("d", "Missing user id.");
    redirect('users.php');
}
?>

<?php
// Update User basic info
if (isset($_POST['update'])) {
    $req_fields = array('name', 'username', 'level');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_user['id'];
        $name = remove_junk($db->escape($_POST['name']));
        $username = remove_junk($db->escape($_POST['username']));
        $level = (int)$db->escape($_POST['level']);
        $status = remove_junk($db->escape($_POST['status']));

        // Prevent changing user_level for level 1 users
        if ($e_user['user_level'] == 1 && $level != $e_user['user_level']) {
            $session->msg('d', "You cannot change the role of a Level 1 user.");
            redirect('edit_user.php?id=' . (int)$e_user['id'], false);
        }

        // Ensure Level 1 users' role and status cannot be changed
        if ($e_user['user_level'] == 1) {
            $level = $e_user['user_level']; // Force Level 1 role
            $status = '1'; // Force Active status
        }

        $sql = "UPDATE users SET name ='{$name}', username ='{$username}', user_level='{$level}', status='{$status}' WHERE id='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Account Updated ");
            redirect('users.php', false);
        } else {
            $session->msg('d', 'Sorry failed to update!');
            redirect('edit_user.php?id=' . (int)$e_user['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_user.php?id=' . (int)$e_user['id'], false);
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
            background-color: #d1fae5; /* Light green color */
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
                <h2 class="text-3xl font-bold">Update <?php echo remove_junk(ucwords($e_user['name'])); ?> Account</h2>
            </div>
            <div class="p-6">
                <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>">

                    <div class="mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-input" name="name" value="<?php echo remove_junk(ucwords($e_user['name'])); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-input" name="username" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="level" class="form-label">User Role</label>
                        <?php if ($e_user['user_level'] == 1) : ?>
                            <input type="text" class="form-input bg-gray-200 cursor-not-allowed" value="Admin" disabled>
                            <input type="hidden" name="level" value="1">
                        <?php else : ?>
                            <select class="form-input" name="level" required>
                                <?php foreach ($groups as $group) : ?>
                                    <option <?php if ($group['group_level'] === $e_user['user_level']) echo 'selected="selected"'; ?>
                                        value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <?php if ($e_user['user_level'] == 1) : ?>
                            <input type="text" class="form-input bg-gray-200 cursor-not-allowed" value="Active" disabled>
                            <input type="hidden" name="status" value="1">
                        <?php else : ?>
                            <select class="form-input" name="status" required>
                                <option value="1" <?php echo ($e_user['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo ($e_user['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="update" class="btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

</body>
</html>
