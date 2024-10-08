<?php
$page_title = 'Edit Product';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);
?>

<?php
$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if (!$product) {
    $session->msg("d", "Missing product id.");
    redirect('products.php');
}
?>

<?php
if (isset($_POST['product'])) {
    $req_fields = array('product-title', 'product-category', 'product-quantity', 'cost-price', 'sale-price');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = (int)$_POST['product-category'];
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['cost-price']));
        $p_sale  = remove_junk($db->escape($_POST['sale-price']));

        // Check if product-photo is set and not empty
        if (isset($_POST['product-photo']) && $_POST['product-photo'] !== "") {
            $media_id = remove_junk($db->escape($_POST['product-photo']));
        } else {
            $media_id = '0'; // Default value if no photo is selected
        }

        $query   = "UPDATE products SET";
        $query  .= " name ='{$p_name}', quantity ='{$p_qty}',";
        $query  .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', category_id ='{$p_cat}', media_id ='{$media_id}'";
        $query  .= " WHERE id ='{$product['id']}'";

        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Product updated ");
            redirect('products.php', false);
        } else {
            $session->msg('d', ' Sorry failed to update!');
            redirect('edit_product.php?id=' . $product['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_product.php?id=' . $product['id'], false);
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
        .custom-header {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="mt-6 ml-6">
    <div class="w-3/6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="custom-header p-4">
                <div class="flex items-center">
                    <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
                    <h2 class="text-3xl font-bold ml-2">EDIT PRODUCT</h2>
                </div>
            </div>
            <div class="p-4">
                <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" class="clearfix">
                    <div class="mb-4">
                        <label for="product-title" class="block text-gray-700 text-sm font-bold mb-2">Product Name</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="product-title" value="<?php echo remove_junk($product['name']); ?>" placeholder="Product Name" required>
                    </div>

                    <div class="mb-4">
                        <label for="product-category" class="block text-gray-700 text-sm font-bold mb-2">Product Category</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="product-category" required>
                            <option value="">Select Product Category</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id'] ?>" <?php if ($cat['id'] === $product['category_id']) echo 'selected'; ?>>
                                    <?php echo $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="product-photo" class="block text-gray-700 text-sm font-bold mb-2">Product Photo</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="product-photo">
                            <option value="">Select Product Photo</option>
                            <?php foreach ($all_photo as $photo): ?>
                                <option value="<?php echo (int)$photo['id'] ?>" <?php if ($photo['id'] === $product['media_id']) echo 'selected'; ?>>
                                    <?php echo $photo['file_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="product-quantity" class="block text-gray-700 text-sm font-bold mb-2">Product Quantity</label>
                        <input type="number" class="form-control border rounded w-full py-2 px-3" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>" placeholder="Product Quantity" required>
                    </div>

                    <div class="mb-4">
                        <label for="cost-price" class="block text-gray-700 text-sm font-bold mb-2">Cost Price</label>
                        <div class="flex">
                            <span class="input-group-addon">₱</span>
                            <input type="number" class="form-control border rounded w-full py-2 px-5" name="cost-price" value="<?php echo remove_junk($product['buy_price']); ?>" placeholder="Cost Price" required>
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="sale-price" class="block text-gray-700 text-sm font-bold mb-2">Selling Price</label>
                        <div class="flex">
                            <span class="input-group-addon">₱</span>
                            <input type="number" class="form-control border rounded w-full py-2 px-3" name="sale-price" value="<?php echo remove_junk($product['sale_price']); ?>" placeholder="Selling Price" required>
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="product" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Update Product
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
