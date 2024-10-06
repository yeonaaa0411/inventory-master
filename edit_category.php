<?php
$page_title = 'Edit category';
require_once('includes/load.php');
// Check user permissions
page_require_level(2);

// Display all categories
$category = find_by_id('categories', (int)$_GET['id']);
if (!$category) {
    $session->msg("d", "Missing category id.");
    redirect('categories.php'); // Redirect to categories if ID is missing
}

if (isset($_POST['edit_cat'])) {
    $req_field = array('category-name');
    validate_fields($req_field);
    $cat_name = remove_junk($db->escape($_POST['category-name']));
    
    if (empty($errors)) {
        // Check if the category name is the same as the existing one
        if ($cat_name === $category['name']) {
            $session->msg("d", "No changes were made, Please provide a different name.");
            // Stay on the same page
        } else {
            $sql = "UPDATE categories SET name='{$cat_name}' WHERE id='{$category['id']}'";
            $result = $db->query($sql);
            
            if ($result && $db->affected_rows() === 1) {
                $session->msg("s", "Successfully updated category");
                redirect('categories.php', false); // Redirect to categories on success
            } else {
                $session->msg("d", "Sorry! Failed to update");
                // Stay on the same page
            }
        }
    } else {
        $session->msg("d", $errors);
        // Stay on the same page
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($category['name'])); ?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_category.php?id=<?php echo (int)$category['id']; ?>">
           <div class="form-group">
               <input type="text" class="form-control" name="category-name" value="<?php echo remove_junk(ucfirst($category['name'])); ?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update category</button>
       </form>
       </div>
     </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>
