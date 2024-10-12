<?php
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(1);

  $delete_id = delete_by_id('users', (int)$_GET['id']);
  if ($delete_id) {
      $session->msg("s", "User deleted.");
  } else {
      $session->msg("d", "User deletion failed or missing parameter.");
  }
  redirect('users.php'); // Redirect to users.php after the action

