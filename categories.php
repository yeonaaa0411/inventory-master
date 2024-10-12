<?php
$page_title = 'All Categories';
require_once('includes/load.php');
page_require_level(2);

// Handle form submission
if (isset($_POST['add_cat'])) {
    $req_field = array('category-name');
    validate_fields($req_field);
    
    $cat_name = remove_junk($db->escape($_POST['category-name']));
    
    // Check if the category already exists
    $sql = "SELECT * FROM categories WHERE name = '{$cat_name}' LIMIT 1";
    $result = $db->query($sql);

    if ($db->num_rows($result) > 0) {
        // Category already exists
        $session->msg('d', "Category '{$cat_name}' already exists. Please choose a different name.");
        redirect('categories.php', false);
    } elseif (empty($errors)) {
        // Insert the new category
        $insert_sql = "INSERT INTO categories (name) VALUES ('{$cat_name}')";
        if ($db->query($insert_sql)) {
            $session->msg('s', "Category added successfully.");
            redirect('categories.php', false);
        } else {
            $session->msg('d', 'Failed to add the category.');
            redirect('categories.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('categories.php', false);
    }
}

$all_categories = find_all('categories');
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
    th, td { padding: 20px; border-bottom: 1px solid #e2e8f0; }
    th { background-color: #eaf5e9; }

    .header-bg {
        background-color: #eaf5e9; /* Light green color */
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

<div class="grid grid-cols-1 mt-6 mx-5">
  <div class="bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center p-4 header-bg">
      <h2 class="text-3xl font-bold">
        <i class="fas fa-tag mr-2"></i>
        CATEGORIES
      </h2>
    </div>
    <div class="p-4">
      <form method="post" action="categories.php">
        <div class="flex space-x-4">
          <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="category-name" placeholder="Category Name" required>
          <button type="submit" name="add_cat" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 mt-6 mx-5">
  <div class="bg-white shadow-md rounded-lg">
    <div class="p-4">
      <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
          <thead>
            <tr>
              <th class="text-center border px-4 py-2" style="width: 50px;">#</th>
              <th class="border px-4 py-2">Category Name</th>
              <th class="text-center border px-4 py-2" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($all_categories as $cat): ?>
            <tr>
              <td class="text-center border px-4 py-2"><?php echo count_id(); ?></td>
              <td class="border px-4 py-2"><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
              <td class="text-center border px-4 py-2">
                <div class="flex justify-center space-x-2">
                  <a href="edit_category.php?id=<?php echo (int)$cat['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  <a href="delete_category.php?id=<?php echo (int)$cat['id']; ?>" onClick="return confirm('Are you sure you want to delete?');" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
                    <i class="fas fa-trash-alt"></i>
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
</div>

<?php include_once('layouts/footer.php'); ?>

<!-- JavaScript for auto-dismiss pop-ups -->
<script>
  // Auto-dismiss alerts after 3 seconds
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.remove());
  }, 2000);
</script>

</body>
</html>
