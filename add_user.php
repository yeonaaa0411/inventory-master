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
        $password = $_POST['password'];
        $user_level = (int)$db->escape($_POST['level']);

        // Validate password strength
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $session->msg("d", "Your password must be at least 8 characters long and include special character (e.g., @, #, $).");
            redirect('add_user.php', false);
        }

        // Check for existing full-name or username
        $existing_user = find_by_sql("SELECT * FROM users WHERE name = '{$name}' OR username = '{$username}' LIMIT 1");
        if (!empty($existing_user)) {
            $session->msg("d", "Full name or username already exists. Please use a different one.");
            redirect('add_user.php', false);
        }

        // Hash the password (to store securely in the database)
        $hashed_password = sha1(remove_junk($db->escape($password)));

        $query = "INSERT INTO users (name, username, password, user_level, status) VALUES ('{$name}', '{$username}', '{$hashed_password}', '{$user_level}', '1')";

        if ($db->query($query)) {
            // Success: Redirect to users.php
            $session->msg('s', "User account has been created!");
            redirect('users.php', false);
        } else {
            // Failed: Redirect back to add_user.php
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
            background-color: #d1fae5; /* bg-green-50 */
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

        .card {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="mt-6 ml-6">
    <div class="w-full sm:w-2/3 lg:w-1/3">
        <div class="card">
            <div class="custom-header p-4">
                <h2 class="text-3xl font-bold">Add New User</h2>
            </div>
            <div class="p-6">
                <form method="post" action="add_user.php">
                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-input" name="full-name" placeholder="Full Name" required>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-input" name="username" placeholder="Username" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-input" name="password" placeholder="Password" required>
                    </div>

                    <div class="mb-4">
                        <label for="level" class="form-label">User Role</label>
                        <select class="form-input" name="level" required>
                            <?php foreach ($groups as $group) : ?>
                                <option value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="add_user" class="btn-primary">
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
