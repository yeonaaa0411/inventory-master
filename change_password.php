<?php include_once('layouts/header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Custom styles */
    .input-field {
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .input-field:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
    }
  </style>
</head>
<body class="bg-gray-50">

<?php
  $page_title = 'Change Password';
  require_once('includes/load.php');
  page_require_level(2);
?>
<?php $user = current_user(); ?>

<?php
  if (isset($_POST['update'])) {
    $req_fields = array('new-password', 'old-password', 'confirm-password', 'id');
    validate_fields($req_fields);

    if (empty($errors)) {
      // Check if the old password matches
      if (sha1($_POST['old-password']) !== current_user()['password']) {
        $session->msg('d', "Your old password does not match");
        redirect('change_password.php', false);
      }

      // Get the new password and confirm password
      $new_password = remove_junk($db->escape($_POST['new-password']));
      $confirm_password = remove_junk($db->escape($_POST['confirm-password']));

      // Check if new password and confirm password match
      if ($new_password !== $confirm_password) {
        $session->msg('d', "New password and confirm password do not match.");
        redirect('change_password.php', false);
      }

      // Validate the new password (min 6 characters and at least one special character)
      if (!preg_match('/^(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $new_password)) {
        $session->msg('d', "New password must be at least 6 characters long and include a special character (e.g., @, $, #, %).");
        redirect('change_password.php', false);
      }

      // If password passes validation, hash the new password
      $new = sha1($new_password);  // Hash the new password before saving it
      $id = (int)$_POST['id'];
      $sql = "UPDATE users SET password ='{$new}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);
      
      // Check if the password was updated successfully
      if ($result && $db->affected_rows() === 1) {
        $session->logout();
        $session->msg('s', "Login with your new password.");
        redirect('index.php', false);
      } else {
        $session->msg('d', 'Sorry, failed to update!');
        redirect('change_password.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('change_password.php', false);
    }
  }
?>



<div class="flex items-center justify-center py-12 bg-gray-50">
  <div class="bg-white shadow-xl rounded-lg w-full max-w-md p-8">
    <div class="text-center mb-8">
      <h3 class="text-3xl font-semibold text-gray-800">Change Your Password</h3>
      <p class="text-gray-500">Ensure your new password is secure and meets the required criteria.</p>
    </div>

    <?php echo display_msg($msg); ?>

    <form method="post" action="change_password.php" class="space-y-6">
      <div class="mb-4">
        <label for="oldPassword" class="block text-sm font-medium text-gray-700">Old Password</label>
        <input type="password" name="old-password" id="oldPassword" class="input-field border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700" placeholder="Enter old password">
      </div>

      <div class="mb-4">
        <label for="newPassword" class="block text-sm font-medium text-gray-700">New Password</label>
        <input type="password" name="new-password" id="newPassword" class="input-field border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700" placeholder="Enter new password">
      </div>

      <div class="mb-4">
        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
        <input type="password" name="confirm-password" id="confirmPassword" class="input-field border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700" placeholder="Confirm new password">
      </div>

      <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">

      <div class="flex justify-center">
        <button type="submit" name="update" class="bg-blue-600 text-white py-3 px-8 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Change Password</button>
      </div>
    </form>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
