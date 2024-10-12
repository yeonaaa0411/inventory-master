<?php
  $page_title = 'All User';
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(1);
  page_require_level(2);
  //pull out all user from database
  $all_users = find_all_user();
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
    /* Custom styles for the table */
    th, td {
      padding: 20px;
      border-bottom: 1px solid #e2e8f0;
      word-wrap: break-word; /* Allow long words to wrap */
    }
    th {
      background-color: #eaf5e9; /* Light green color */
    }
    .table-cell-wrap {
      white-space: normal; /* Allows text to wrap */
      word-break: break-word; /* Break long words */
    }
  </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>

<div class="flex justify-center mt-6">
   <div class="w-11/12 md:w-2/3">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<!-- Users Panel -->
<div class="grid grid-cols-1 mt-1 mx-5">
  <div class="bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 class="text-3xl font-bold">
        <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
        USERS
      </h2>
      <a href="add_user.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New User</a>
    </div>
    <div class="p-4">
      <table class="min-w-full border-collapse">
        <thead>
          <tr>
            <th class="text-center border px-4 py-2">#</th>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Username</th>
            <th class="text-center border px-4 py-2">User Role</th>
            <th class="text-center border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Last Login</th>
            <th class="text-center border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center border px-4 py-2"><?php echo count_id();?></td>
           <td class="border px-4 py-2 table-cell-wrap"><?php echo remove_junk(ucwords($a_user['name']))?></td>
           <td class="border px-4 py-2 table-cell-wrap"><?php echo remove_junk(ucwords($a_user['username']))?></td>
           <td class="text-center border px-4 py-2 table-cell-wrap"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
           <td class="text-center border px-4 py-2">
             <?php if($a_user['status'] === '1'): ?>
              <span class="bg-green-500 text-white px-2 py-1 rounded"><?php echo "Active"; ?></span>
             <?php else: ?>
              <span class="bg-red-500 text-white px-2 py-1 rounded"><?php echo "Deactive"; ?></span>
             <?php endif;?>
           </td>
           <td class="border px-4 py-2"><?php echo read_date($a_user['last_login'])?></td>
           <td class="text-center border px-4 py-2">
             <div class="flex justify-center space-x-2">
                <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" onClick="return confirm('Are you sure you want to delete this user?');" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" data-toggle="tooltip" title="Delete">
                  <i class="glyphicon glyphicon-trash"></i>
                </a>
             </div>
           </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>

