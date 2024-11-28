<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "My Profile"; ?></title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<?php
  $page_title = 'My Profile';
  require_once('includes/load.php');
  page_require_level(2);
  
  $user_id = (int)$_GET['id'];
  if (empty($user_id)):
    redirect('edit_account.php', false);
  else:
    $user_p = find_by_id('users', $user_id);
  endif;
?>

<?php include_once('layouts/header.php'); ?>

<div class=" justify-left min-h-screen">
  <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-md">
    <div class="bg-[#eaf5e9] p-6 text-center">
      <img class="w-32 h-32 rounded-full mx-auto border-4 border-white" 
           src="uploads/users/<?php echo $user_p['image']; ?>" 
           alt="User Profile Picture">
      <h3 class="text-xl font-semibold text-gray-800 mt-4"><?php echo first_character($user_p['name']); ?></h3>
    </div>
    <div class="p-6">
      <?php if ($user_p['id'] === $user['id']): ?>
        <div class="flex justify-center mt-4">
          <a href="edit_account.php" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-edit mr-2"></i>Edit Profile
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<!-- FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
