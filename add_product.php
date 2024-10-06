<?php
$page_title = 'Add Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);

$all_categories = find_all('categories');
$all_photo = find_all('media');

if (isset($_POST['add_product'])) {
    $req_fields = array('product-title', 'product-category', 'product-quantity', 'cost-price', 'sale-price');
    validate_fields($req_fields);
    if (empty($errors)) {
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = remove_junk($db->escape($_POST['product-category']));
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['cost-price']));
        $p_sale  = remove_junk($db->escape($_POST['sale-price']));
        $media_id = (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") ? '0' : remove_junk($db->escape($_POST['product-photo']));
        $date    = make_date();
        
        $query  = "INSERT INTO products (name, quantity, buy_price, sale_price, category_id, media_id, date) VALUES ('{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}') ON DUPLICATE KEY UPDATE name='{$p_name}'";
        
        if ($db->query($query)) {
            $product = last_id("products");
            $product_id = $product['id'];
            if ($product_id == 0) {
                $session->msg('d', ' Sorry failed to added!');
                redirect('add_product.php', false);
            }

            $quantity = $p_qty;
            $comments = "initial stock";
            $sql  = "INSERT INTO stock (product_id, quantity, comments, date) VALUES ('{$product_id}', '{$quantity}', '{$comments}', '{$date}')";
            $result = $db->query($sql);
            if ($result && $db->affected_rows() === 1) {
                $session->msg('s', "Product added ");
                redirect('products.php', false);
            }
        } else {
            $session->msg('d', ' Sorry failed to added!');
            redirect('add_product.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-8 offset-md-2"> <!-- Center the form on the page -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add New Product</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_product.php" class="clearfix">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-th-large"></i>
                            </span>
                            <input type="text" class="form-control" name="product-title" placeholder="Product Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="product-category" required>
                                    <option value="">Select Product Category</option>
                                    <?php foreach ($all_categories as $cat): ?>
                                        <option value="<?php echo (int)$cat['id'] ?>">
                                            <?php echo $cat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="product-photo">
                                    <option value="">Select Product Photo</option>
                                    <?php foreach ($all_photo as $photo): ?>
                                        <option value="<?php echo (int)$photo['id'] ?>">
                                            <?php echo $photo['file_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-shopping-cart"></i>
                                    </span>
                                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">₱</span>
                                    <input type="number" step="0.01" class="form-control" name="cost-price" placeholder="Cost Price" required>
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">₱</span>
                                    <input type="number" step="0.01" class="form-control" name="sale-price" placeholder="Selling Price" required>
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right">
                        <button type="submit" name="add_product" class="btn btn-danger">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
