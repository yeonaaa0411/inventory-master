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
  $page_title = 'Edit Account';
  require_once('includes/load.php');
  page_require_level(3);
?>

<?php
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
    if (empty($errors)) {
        $id = (int)$_SESSION['user_id'];
        $name = remove_junk($db->escape($_POST['name']));
        $username = remove_junk($db->escape($_POST['username']));
        $sql = "UPDATE users SET name ='{$name}', username ='{$username}' WHERE id='{$id}'";
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Account updated.");
            redirect('edit_account.php', false);
        } else {
            $session->msg('d', 'Sorry, failed to update.');
            redirect('edit_account.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_account.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="container mx-auto mt-10">
  <div class="flex flex-wrap justify-left -mx-3">
    <!-- Profile Picture Section -->
    <div class="w-full md:w-3/4 lg:w-2/5 px-3 mb-6">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden" style="min-height: 300px;">
        <div class="text-center py-8 bg-gray-200">
          <img id="profileImage" class="w-32 h-32 rounded-full mx-auto border-4 border-white"
               src="uploads/users/<?php echo $user['image']; ?>" 
               alt="User Profile Picture">
          <h2 class="text-2xl font-semibold mt-4"><?php echo first_character($user['name']); ?></h2>
          <button onclick="openModal()" id="changePhotoButton" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-camera mr-2"></i> Change Photo
          </button>
        </div>
      </div>
    </div>

    <!-- Account Information Section -->
    <div class="w-full md:w-3/4 lg:w-2/5 px-3 mb-6">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden" style="min-height: 300px;">
        <div class="bg-gray-200 p-4 flex items-center">
          <i class="glyphicon glyphicon-edit mr-2"></i>
          <h3 class="text-xl font-semibold">Edit My Account</h3>
        </div>
        <div class="p-6">
          <form method="post" action="edit_account.php">
            <div class="mb-4">
              <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
              <input type="text" name="name" class="border rounded w-full py-2 px-3 text-gray-700" value="<?php echo remove_junk(ucwords($user['name'])); ?>">
            </div>
            <div class="mb-4">
              <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
              <input type="text" name="username" class="border rounded w-full py-2 px-3 text-gray-700" value="<?php echo remove_junk(ucwords($user['username'])); ?>">
            </div>
            <div class="flex justify-between items-center mt-8">
              <a href="change_password.php" class="text-blue-500 hover:underline">Change Password</a>
              <button type="submit" name="update" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
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
    <h2 class="text-xl font-semibold mb-4">Change Profile Photo</h2>
    <form action="edit_account.php" method="POST" enctype="multipart/form-data">
      <div class="mb-4">
        <label for="file_upload" class="block text-gray-700 text-sm font-bold mb-2">Select an Image</label>
        <input type="file" name="file_upload" id="file_upload" accept="image/*" class="block w-full text-sm text-gray-500 border border-gray-300 rounded p-2" onchange="updateButtonText()">
      </div>
      <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
      <div class="flex justify-end space-x-2">
        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
        <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById('photoModal').classList.remove('hidden');
  }

  function closeModal() {
    document.getElementById('photoModal').classList.add('hidden');
  }

  function updateButtonText() {
    const fileInput = document.getElementById('file_upload');
    const changePhotoButton = document.getElementById('changePhotoButton');
    if (fileInput.files.length > 0) {
      const fileName = fileInput.files[0].name;
      changePhotoButton.textContent = `Selected: ${fileName}`;
    } else {
      changePhotoButton.innerHTML = '<i class="fas fa-camera mr-2"></i> Change Photo';
    }
  }
</script>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
