<?php
$page_title = 'Add User';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(1);
$groups = find_all('user_groups');
?>

<?php
if (isset($_POST['add_user'])) {
    $req_fields = array('full-name', 'username', 'password', 'level');
    validate_fields($req_fields);

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['full-name']));
        $username = remove_junk($db->escape($_POST['username']));
        $password = remove_junk($db->escape($_POST['password']));
        $user_level = (int)$db->escape($_POST['level']);
        $password = sha1($password);

        $query = "INSERT INTO users (name, username, password, user_level, status) VALUES ('{$name}', '{$username}', '{$password}', '{$user_level}', '1')";

        if ($db->query($query)) {
            // success
            $session->msg('s', "User account has been created!");
            redirect('add_user.php', false);
        } else {
            // failed
            $session->msg('d', 'Sorry, failed to create account!');
            redirect('add_user.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_user.php', false);
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
        .custom-header {
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
            <div class="custom-header p-4">
                <div class="flex items-center">
                    <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
                    <h2 class="text-3xl font-bold ml-2">ADD NEW USER</h2>
                </div>
            </div>
            <div class="p-4">
                <form method="post" action="add_user.php">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="full-name" placeholder="Full Name" required>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="username" placeholder="Username" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" class="form-control border rounded w-full py-2 px-3" name="password" placeholder="Password" required>
                    </div>

                    <div class="mb-4">
                        <label for="level" class="block text-gray-700 text-sm font-bold mb-2">User Role</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="level" required>
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="add_user" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Add User
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
