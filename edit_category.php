<?php
// Include necessary files and authenticate user
require_once('includes/load.php');
page_require_level(2);

// Check if the request is an AJAX POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the POST request
    $cat_id = (int)$_POST['category-id'];
    $cat_name = remove_junk($db->escape($_POST['category-name']));

    // Initialize error array
    $errors = [];

    if (empty($cat_name)) {
        $errors[] = 'Category name cannot be empty.';
    }

    // If no errors, proceed with the category update
    if (empty($errors)) {
        // Check if the category name is different from the current one
        if ($cat_name === get_category_name($cat_id)) {
            echo json_encode(['success' => false, 'message' => 'No changes were made.']);
            exit;
        } else {
            // Update the category name
            $sql = "UPDATE categories SET name = '{$cat_name}' WHERE id = {$cat_id}";
            $result = $db->query($sql);

            // Check if the category was updated successfully
            if ($result && $db->affected_rows() === 1) {
                echo json_encode(['success' => true, 'message' => 'Category updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update category.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    }
    exit;
}

// Fetch the current category name from the database
function get_category_name($id) {
    global $db;
    $sql = "SELECT name FROM categories WHERE id = {$id} LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->num_rows($result) > 0) ? $db->fetch_assoc($result)['name'] : '';
}
?>
