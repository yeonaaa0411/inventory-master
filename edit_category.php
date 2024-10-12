<?php
$page_title = 'Edit category';
require_once('includes/load.php');
// Check user permissions
page_require_level(2);

// Display the category
$category = find_by_id('categories', (int)$_GET['id']);
if (!$category) {
    $session->msg("d", "Missing category id.");
    redirect('categories.php');
}

if (isset($_POST['edit_cat'])) {
    $req_field = array('category-name');
    validate_fields($req_field);
    $cat_name = remove_junk($db->escape($_POST['category-name']));

    if (empty($errors)) {
        if ($cat_name === $category['name']) {
            $session->msg("d", "No changes were made, Please provide a different name.");
        } else {
            $sql = "UPDATE categories SET name='{$cat_name}' WHERE id='{$category['id']}'";
            $result = $db->query($sql);
            if ($result && $db->affected_rows() === 1) {
                $session->msg("s", "Successfully updated category");
                redirect('categories.php', false);
            } else {
                $session->msg("d", "Sorry! Failed to update");
            }
        }
    } else {
        $session->msg("d", $errors);
    }
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
    .custom-class { color: #eaf5e9; }
    .header-bg {
        background-color: #eaf5e9; /* Light green color */
    }
  </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="mt-6 ml-6">
    <div class="w-2/6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-4 header-bg">
                <h2 class="text-3xl font-bold">
                    <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
                    EDIT CATEGORY
                </h2>
            </div>
            <div class="p-4">
                <form method="post" action="edit_category.php?id=<?php echo (int)$category['id']; ?>">
                    <div class="mb-4">
                        <label for="category-name" class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="category-name" value="<?php echo remove_junk(ucfirst($category['name'])); ?>">
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="edit_cat" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

</body>
</html>
