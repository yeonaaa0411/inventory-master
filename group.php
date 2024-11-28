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

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
          <i class="fas fa-th mr-2"></i> All Groups
        </h2>
      </div>
      <div class="overflow-x-auto px-6 py-4">
        <table class="min-w-full table-auto border-collapse table-row-height">
          <thead>
            <tr class="border-b bg-gray-100">
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">#</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Group Name</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Group Level</th>
              <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($all_groups) > 0): ?>
              <?php foreach($all_groups as $a_group): ?>
                <tr class="hover:bg-gray-50">
                  <td class="text-center px-4 py-3"><?php echo count_id(); ?></td>
                  <td class="text-center px-4 py-3 table-cell-wrap"><?php echo remove_junk(ucwords($a_group['group_name'])); ?></td>
                  <td class="text-center px-4 py-3"><?php echo remove_junk(ucwords($a_group['group_level'])); ?></td>
                  <td class="text-center px-4 py-3">
                    <div class="flex justify-center space-x-2">
                      <!-- Edit Button -->
                      <a href="edit_group.php?id=<?php echo (int)$a_group['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">No groups found.</td>
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
