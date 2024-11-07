<?php
$page_title = 'All Group';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(1);

// Fetch all groups
$all_groups = find_all('user_groups');

// Check if the result is not empty
if (!$all_groups) {
    $all_groups = []; // Set it to an empty array if no groups found
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
    /* Custom styles */
    .custom-class { color: #eaf5e9; }
    th, td {
      padding: 20px;
      border-bottom: 1px solid #e2e8f0;
      white-space: nowrap; /* Prevent text wrapping */
      overflow: hidden; /* Hide overflow */
      text-overflow: ellipsis; /* Show ellipsis for long text */
    }
    th {
      background-color: #eaf5e9; /* Light green color */
    }
    .table-cell-wrap {
      max-width: 150px; /* Set a maximum width for long content */
    }
  </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>

<div class="flex justify-center">
   <div class="w-11/12 md:w-2/3">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<!-- Groups Panel -->
<div class="grid grid-cols-1 mt-6 mx-5">
  <div class="bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 class="text-3xl font-bold">
        <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
        GROUPS
      </h2>
      <a href="add_group.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Group</a>
    </div>
    <div class="p-4">
      <table class="min-w-full border-collapse">
        <thead>
          <tr>
            <th class="text-center border px-4 py-2" style="width: 50px;">#</th>
            <th class="border px-4 py-2">Group Name</th>
            <th class="text-center border px-4 py-2" style="width: 20%;">Group Level</th>
            <th class="text-center border px-4 py-2" style="width: 15%;">Status</th>
            <th class="text-center border px-4 py-2" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (count($all_groups) > 0): ?>
          <?php foreach($all_groups as $a_group): ?>
            <tr>
             <td class="text-center border px-4 py-2"><?php echo count_id(); ?></td>
             <td class="border px-4 py-2 table-cell-wrap"><?php echo remove_junk(ucwords($a_group['group_name'])); ?></td>
             <td class="text-center border px-4 py-2">
               <?php echo remove_junk(ucwords($a_group['group_level'])); ?>
             </td>
             <td class="text-center border px-4 py-2">
             <?php if($a_group['group_status'] === '1'): ?>
              <span class="bg-green-500 text-white px-2 py-1 rounded">Active</span>
            <?php else: ?>
              <span class="bg-red-500 text-white px-2 py-1 rounded">Deactive</span>
            <?php endif; ?>
             </td>
             <td class="text-center border px-4 py-2">
               <div class="flex justify-center space-x-2">
                  <a href="edit_group.php?id=<?php echo (int)$a_group['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" data-toggle="tooltip" title="Edit">
                    <i class="glyphicon glyphicon-pencil"></i>
                  </a>
                  <a href="delete_group.php?id=<?php echo (int)$a_group['id']; ?>" onClick="return confirm('Are you sure you want to delete this group?');" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" data-toggle="tooltip" title="Delete">
                    <i class="glyphicon glyphicon-trash"></i>
                  </a>
               </div>
             </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center border px-4 py-2">No groups found</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
