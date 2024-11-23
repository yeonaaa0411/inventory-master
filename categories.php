<?php
$page_title = 'All Categories';
require_once('includes/load.php');
page_require_level(2);

// Handle form submission for adding a category
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

// Handle form submission for editing a category
if (isset($_POST['edit_cat'])) {
    $cat_id = (int)$_POST['category-id'];
    $cat_name = remove_junk($db->escape($_POST['category-name']));
    
    // Check if the category name already exists (optional check)
    $sql = "SELECT * FROM categories WHERE name = '{$cat_name}' AND id != {$cat_id} LIMIT 1";
    $result = $db->query($sql);
    if ($db->num_rows($result) > 0) {
        $session->msg('d', "Category '{$cat_name}' already exists. Please choose a different name.");
        redirect('categories.php', false);
    } elseif (empty($errors)) {
        // Update the category
        $update_sql = "UPDATE categories SET name = '{$cat_name}' WHERE id = {$cat_id}";
        if ($db->query($update_sql)) {
            $session->msg('s', "Category updated successfully.");
            redirect('categories.php', false);
        } else {
            $session->msg('d', 'Failed to update the category.');
            redirect('categories.php', false);
        }
    } else {
        $session->msg('d', 'Invalid input or empty fields.');
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

    /* Highlight the row on hover */
    tr:hover {
        background-color: #f4f4f9; /* Light gray color */
        cursor: pointer;
    }

    /* Modal style */
    .modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 50; justify-content: center; align-items: center; background-color: rgba(0, 0, 0, 0.5); }
    .modal-content { background-color: #fff; padding: 20px; border-radius: 8px; max-width: 500px; width: 100%; }
    .modal.active { display: flex; }
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
      <!-- Button to trigger the modal -->
      <button id="openModal" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Category</button>
    </div>
  </div>
</div>

<!-- Modal for adding a category -->
<div id="categoryModal" class="modal">
  <div class="modal-content">
    <h3 class="text-xl font-bold mb-4" id="modalTitle">Add Category</h3>
    <form method="post" action="categories.php">
      <input type="hidden" name="category-id" id="category-id">
      <input type="text" name="category-name" id="category-name" placeholder="Category Name" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full mb-4" required>
      <div class="flex justify-end">
        <button type="submit" name="add_cat" id="addCategoryButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Category</button>
        <button type="button" id="closeModal" class="bg-red-500 text-white px-4 py-2 rounded ml-2 hover:bg-red-600">Cancel</button>
      </div>
    </form>
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
                  <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit"
                          onclick="openEditModal(<?php echo $cat['id']; ?>, '<?php echo $cat['name']; ?>')">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
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

<!-- JavaScript to manage the modal -->
<script>
  // Get modal and buttons
  const modal = document.getElementById('categoryModal');
  const openModalButton = document.getElementById('openModal');
  const closeModalButton = document.getElementById('closeModal');
  const addCategoryButton = document.getElementById('addCategoryButton');
  const modalTitle = document.getElementById('modalTitle');

  // Open modal when "Add Category" button is clicked
  openModalButton.addEventListener('click', () => {
    modal.classList.add('active');
    modalTitle.textContent = "Add Category";
    addCategoryButton.textContent = "Add Category";
  });

  // Close modal when "Cancel" button is clicked
  closeModalButton.addEventListener('click', () => {
    modal.classList.remove('active');
  });

  // Function to open modal for editing category
  function openEditModal(catId, catName) {
    document.getElementById('category-id').value = catId;
    document.getElementById('category-name').value = catName;
    modal.classList.add('active');
    modalTitle.textContent = "Edit Category";
    addCategoryButton.textContent = "Update Category";
  }
</script>

</body>
</html>
