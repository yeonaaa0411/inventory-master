<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

<?php
  $page_title = 'Edit Account';
  require_once('includes/load.php');
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

<script type="text/javascript">
    // Function to show the modal
    function openModal() {
        document.getElementById("photoModal").classList.remove("hidden");
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById("photoModal").classList.add("hidden");
    }

    // Optional: Close the modal if clicked outside the modal content
    window.onclick = function(event) {
        if (event.target === document.getElementById("photoModal")) {
            closeModal();
        }
    }
</script>

<?php if (isset($session->msg)) : ?>
<div class="text-red-500 text-sm mt-2 text-center">
  <?php
    if (is_array($session->msg)) {
        echo implode("<br>", $session->msg);
    } else {
        echo $session->msg;
    }
  ?>
</div>
<?php endif; ?>


<?php include_once('layouts/header.php'); ?>

<div class="container mx-auto mt-10 px-4">
  <div class="flex flex-wrap justify-center -mx-4">
    <!-- Profile Picture Section -->
    <div class="w-full sm:w-3/4 lg:w-1/3 px-4 mb-6">
      <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="text-center py-8 bg-gray-200">
          <img id="profileImage" class="w-40 h-40 rounded-full mx-auto border-4 border-white shadow-lg"
               src="uploads/users/<?php echo $user['image']; ?>" alt="User Profile Picture">
          <h2 class="text-3xl font-semibold mt-4 text-gray-800"><?php echo first_character($user['name']); ?></h2>
          <button onclick="openModal()" id="changePhotoButton" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
            <i class="fas fa-camera mr-2"></i> Change Photo
          </button>
        </div>
      </div>
    </div>

    <!-- Account Information Section -->
    <div class="w-full sm:w-3/4 lg:w-1/3 px-4 mb-6">
      <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-gray-200 p-6 flex items-center">
          <i class="glyphicon glyphicon-edit mr-3 text-blue-500"></i>
          <h3 class="text-2xl font-semibold text-gray-800">Edit My Account</h3>
        </div>
        <div class="p-6">
          <form method="post" action="edit_account.php">
            <div class="mb-6">
              <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
              <input type="text" name="name" class="border rounded-lg w-full py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo remove_junk(ucwords($user['name'])); ?>" required>
            </div>
            <div class="mb-6">
              <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
              <input type="text" name="username" class="border rounded-lg w-full py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo remove_junk(ucwords($user['username'])); ?>" required>
            </div>

            <?php if (isset($session->msg)) : ?>
              <div class="text-red-500 text-sm mb-4">
                <?php
                  if (is_array($session->msg)) {
                      echo implode("<br>", $session->msg);
                  } else {
                      echo $session->msg;
                  }
                ?>
              </div>
            <?php endif; ?>

            <div class="flex justify-between items-center mt-8">
              <a href="change_password.php" class="text-blue-500 hover:underline">Change Password</a>
              <button type="submit" name="update" class="bg-green-500 text-white px-8 py-3 rounded-lg hover:bg-green-600 transition duration-300">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for changing profile picture -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-4">Change Profile Photo</h2>
    <form action="edit_account.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
      <div class="mb-6">
        <label for="file_upload" class="block text-gray-700 text-sm font-medium mb-2">Select an Image</label>
        <input type="file" name="file_upload" id="file_upload" class="w-full text-sm focus:ring-2 focus:ring-blue-500">
      </div>
      <div class="flex justify-end">
        <button type="submit" name="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
          Upload
        </button>
        <button type="button" class="ml-4 bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition duration-300" onclick="closeModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
