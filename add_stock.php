<?php
$page_title = 'Add Stock';
require_once('includes/load.php');
// Check user permission level
page_require_level(2);

$all_products = find_all('products');

if (isset($_POST['add_stock'])) {
    $req_field = array('product_id', 'quantity');
    validate_fields($req_field);
    $product_id = remove_junk($db->escape($_POST['product_id']));
    $quantity = remove_junk($db->escape($_POST['quantity']));
    $comments = remove_junk($db->escape($_POST['comments']));
    $current_date = make_date();

    if (empty($errors)) {
        $sql  = "INSERT INTO stock (product_id, quantity, comments, date)";
        $sql .= " VALUES ('{$product_id}', '{$quantity}', '{$comments}', '{$current_date}')";
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            increase_product_qty($quantity, $product_id);
            $session->msg("s", "Successfully Added");
            redirect('stock.php', false);
        } else {
            $session->msg("d", "Sorry Failed to insert.");
            redirect('add_stock.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_stock.php', false);
    }
}

include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add Stock</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="product_id" class="control-label">Select Product</label>
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="0">Select Product</option>
                            <?php foreach ($all_products as $product): ?>
                                <option value="<?php echo $product['id']; ?>">
                                    <?php echo $product['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-shopping-cart"></i>
                            </span>
                            <input type="number" class="form-control" name="quantity" placeholder="Product Quantity" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="comments" placeholder="Comments">
                    </div>

                    <button type="submit" name="add_stock" class="btn btn-primary">Add to Inventory</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
