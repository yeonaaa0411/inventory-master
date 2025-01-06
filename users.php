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
  
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Custom CSS -->
  <style>
    th, td {
      padding-top: 1.25rem;
      padding-bottom: 1.25rem;
    }

    th {
      background-color: #f4fafb;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    tr:hover {
      background-color: #f9fafb;
    }

    .header-bg {
      background-color: #f4fafb;
    }

    .table-row-height th, .table-row-height td {
      padding-top: 1.25rem;
      padding-bottom: 1.25rem;
    }

    .table-cell-wrap {
      max-width: 150px;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
    }

    .custom-class {
      color: #eaf5e9;
    }
  </style>
</head>
<body class="bg-gray-50">

  <?php include_once('layouts/header.php'); ?>

  <div class="w-full px-4 py-6">
    <?php echo display_msg($msg); ?>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <div class="flex justify-between items-center p-6 bg-green-50">
        <h2 class="text-2xl font-semibold text-gray-800">
          <i class="fas fa-user mr-2"></i> All Users
        </h2>
        <a href="add_user.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New User</a>
      </div>
      <div class="overflow-x-auto px-6 py-4">
        <table class="min-w-full table-auto border-collapse table-row-height">
          <thead>
            <tr class="border-b bg-gray-100">
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">#</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Name</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Username</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">User Role</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Status</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Last Login</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($all_users as $a_user): ?>
              <tr class="hover:bg-gray-50">
                <td class="text-center px-4 py-3"><?php echo count_id();?></td>
                <td class="text-center px-4 py-3 table-cell-wrap"><?php echo remove_junk(ucwords($a_user['name']))?></td>
                <td class="text-center px-4 py-3 table-cell-wrap"><?php echo remove_junk(ucwords($a_user['username']))?></td>
                <td class="text-center px-4 py-3 table-cell-wrap"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
                <td class="text-center px-4 py-3">
                  <?php if($a_user['status'] === '1'): ?>
                    <span class="bg-green-500 text-white px-2 py-1 rounded"><?php echo "Active"; ?></span>
                  <?php else: ?>
                    <span class="bg-red-500 text-white px-2 py-1 rounded"><?php echo "Inactive"; ?></span>
                  <?php endif;?>
                </td>
                <td class="text-center px-4 py-3"><?php echo read_date($a_user['last_login'])?></td>
                <td class="text-center px-4 py-3">
                  <div class="flex justify-center space-x-2">
                    <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <?php if ((int)$a_user['user_level'] !== 1): ?>
                      <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" onClick="return confirm('Are you sure you want to delete this user?');" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
                        <i class="fas fa-trash"></i>
                      </a>
                    <?php endif; ?>
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
