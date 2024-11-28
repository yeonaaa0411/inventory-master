<?php
  // Start the session at the very beginning
  session_start();

  // Now include other files
  require_once('includes/load.php'); // Include the required files
  include_once('layouts/header.php');

  $page_title = 'Edit Account';
  page_require_level(3);

  // Handle photo upload
  if (isset($_POST['submit'])) {
      $photo = new Media();
      $user_id = (int)$_POST['user_id'];
      $photo->upload($_FILES['file_upload']);
      if ($photo->process_user($user_id)) {
          $session->msg('s', 'Photo has been uploaded.');
          redirect('edit_account.php');
      } else {
          $session->msg('d', join($photo->errors));
          redirect('edit_account.php');
      }
  }

  // Handle account update
  if (isset($_POST['update'])) {
    $req_fields = array('name', 'username');
    validate_fields($req_fields);

    // Validate username: should include special characters (same as in your add_user.php policy)
    if (empty($errors)) {
        $id = (int)$_SESSION['user_id'];
        $name = remove_junk($db->escape($_POST['name']));
        $username = remove_junk($db->escape($_POST['username']));

        // Check if username follows the policy (at least 6 characters and contains a special character)
        if (!preg_match('/^(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $username)) {
            $session->msg("d", "Username must be at least 6 characters long and include a special character (e.g., @, #, $, %).");
            redirect('edit_account.php', false);  // Redirect back to edit_account.php if validation fails
        }

        // Proceed with the update if validation passes
        $sql = "UPDATE users SET name ='{$name}', username ='{$username}' WHERE id='{$id}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
          // Refresh session user data after the update
          $updated_user = find_user_by_id($id);
          $_SESSION['user_id'] = $updated_user['id'];
          $_SESSION['user_name'] = $updated_user['name'];
          $_SESSION['username'] = $updated_user['username'];
      
          // Display success message and show pop-up
          echo "<script type='text/javascript'>
                  alert('Account updated successfully!');
                  window.location.href='profile.php';
                </script>";
          exit(); // Ensures the script stops after redirecting
      } else {
          $session->msg('d', 'Failed to update, No changes were made');
          redirect('edit_account.php', false);  // Redirect back to edit_account.php if the update failed
      }
      
    } else {
        $session->msg("d", $errors);  // Display validation errors if there are any
        redirect('edit_account.php', false);
    }
}
?>
