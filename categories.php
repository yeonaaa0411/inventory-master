<?php
$page_title = 'All Categories';
require_once('includes/load.php');
page_require_level(2);

// Handle form submission for adding a category
if (isset($_POST['add_cat'])) {
  $req_field = ['category-name'];
  validate_fields($req_field);

  $cat_name = remove_junk($db->escape($_POST['category-name']));

  // Check if the category already exists
  $sql = "SELECT * FROM categories WHERE name = '{$cat_name}' LIMIT 1";
  $result = $db->query($sql);

  if ($db->num_rows($result) > 0) {
      $session->msg('d', "Category '{$cat_name}' already exists. Please choose a different name.");
      redirect('categories.php', false);
  } elseif (empty($errors)) {
      // Insert the new category
      $insert_sql = "INSERT INTO categories (name) VALUES ('{$cat_name}')";
      if ($db->query($insert_sql)) {
          $session->msg('s', "Category added successfully.");
      } else {
          $session->msg('d', 'Failed to add the category.');
      }
      redirect('categories.php', false);
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

// Fetch all categories
$sql = "SELECT * FROM categories";
$all_categories = $db->query($sql);
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
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background-color: #f4fafb;
            color: #374151;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .header-bg {
            background-color: #f4fafb;
        }

        .table-container {
            overflow-x-auto;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 50;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 100%;
        }

        .modal.active {
            display: flex;
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
                    <i class="fas fa-tag mr-2"></i> Categories
                </h2>
                <button id="openModal" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Category</button>
            </div>
            <div class="table-container px-6 py-4">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center px-4 py-2 bg-green-50">#</th>
                            <th class="text-left px-4 py-2 bg-green-50">Category Name</th>
                            <th class="text-center px-4 py-2 bg-green-50">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_categories as $cat): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="text-center px-4 py-3"><?php echo $cat['id']; ?></td>
                                <td class="text-left px-4 py-3"><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                                <td class="text-center px-4 py-3">
                                    <div class="flex justify-center space-x-2">
<!-- Edit Button -->
<a href="javascript:void(0);" 
   class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
   onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo addslashes($cat['name']); ?>')">
    <i class="fas fa-pencil-alt"></i>
</a>

                                        <!-- Delete Button -->
                                        <a href="delete_category.php?id=<?php echo $cat['id']; ?>" onClick="return confirm('Are you sure?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
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

<!-- Modal for adding/editing a category -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <h3 class="text-xl font-bold mb-4" id="modalTitle">Add Category</h3>
        <form method="post" action="categories.php">
            <!-- Hidden field for category ID, set only when editing -->
            <input type="hidden" name="category-id" id="category-id" value="<?php echo isset($category) ? $category['id'] : ''; ?>">
            <input type="text" name="category-name" id="category-name" 
                placeholder="Category Name" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full mb-4" 
                value="<?php echo isset($category) ? remove_junk(ucfirst($category['name'])) : ''; ?>" required>
            <div class="flex justify-end">
                <button type="submit" name="<?php echo isset($category) ? 'edit_cat' : 'add_cat'; ?>" id="formSubmitButton" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <?php echo isset($category) ? 'Update Category' : 'Add Category'; ?>
                </button>
                <button type="button" id="closeModal" class="bg-red-500 text-white px-4 py-2 rounded ml-2 hover:bg-red-600">Cancel</button>
            </div>
        </form>
    </div>
</div>


    <script>
const openModal = document.getElementById('openModal');
const closeModal = document.getElementById('closeModal');
const categoryModal = document.getElementById('categoryModal');
const categoryInput = document.getElementById('category-name');
const formSubmitButton = document.getElementById('formSubmitButton');

openModal.addEventListener('click', () => {
    categoryModal.classList.add('active');
    categoryInput.value = ''; // Clear input for adding new category
    document.getElementById('category-id').value = ''; // Ensure ID is empty for new category
    formSubmitButton.setAttribute('name', 'add_cat'); // Set form action to add new category
    document.getElementById('modalTitle').textContent = 'Add Category'; // Change modal title
});

closeModal.addEventListener('click', () => {
    categoryModal.classList.remove('active');
});

// Edit category function (called when edit button is clicked)
// Edit category function (called when edit button is clicked)
function editCategory(id, name) {
    categoryModal.classList.add('active');
    document.getElementById('category-id').value = id;
    categoryInput.value = name;
    formSubmitButton.setAttribute('name', 'edit_cat'); // Set form action to edit category
    document.getElementById('modalTitle').textContent = 'Edit Category'; // Change modal title
}



    </script>
    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
