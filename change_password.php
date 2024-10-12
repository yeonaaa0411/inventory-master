<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<?php
  $page_title = 'Change Password';
  require_once('includes/load.php');
  page_require_level(2);
?>
<?php $user = current_user(); ?>

<?php
  if (isset($_POST['update'])) {
    $req_fields = array('new-password', 'old-password', 'id');
    validate_fields($req_fields);

    if (empty($errors)) {
      if (sha1($_POST['old-password']) !== current_user()['password']) {
        $session->msg('d', "Your old password does not match");
        redirect('change_password.php', false);
      }

      $id = (int)$_POST['id'];
      $new = remove_junk($db->escape(sha1($_POST['new-password'])));
      $sql = "UPDATE users SET password ='{$new}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);
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

<?php include_once('layouts/header.php'); ?>

<div class="flex items-center justify-center" style="margin-top: 20px;">
  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-xl h-[500px]"> <!-- Increased width and height -->
    <div class="text-center mb-4">
      <h3 class="text-2xl font-semibold">Change Your Password</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="change_password.php" class="space-y-4">
      <div class="mb-3">
        <label for="newPassword" class="block text-gray-700 text-sm font-bold mb-1">New Password</label>
        <input type="password" class="border rounded w-full py-2 px-3 text-gray-700" name="new-password" placeholder="New password">
      </div>
      <div class="mb-3">
        <label for="oldPassword" class="block text-gray-700 text-sm font-bold mb-1">Old Password</label>
        <input type="password" class="border rounded w-full py-2 px-3 text-gray-700" name="old-password" placeholder="Old password">
      </div>
      <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
      <div class="flex justify-center">
        <button type="submit" name="update" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Change</button>
      </div>
    </form>
  </div>
</div>


<?php include_once('layouts/footer.php'); ?>
</body>
</html>
