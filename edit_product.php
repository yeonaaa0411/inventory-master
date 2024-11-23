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
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        th, td {
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        th {
            background-color: #eaf5e9;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        tr:hover {
            background-color: #f7fafc;
        }
        .custom-header {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Include header -->
    <?php include_once('layouts/header.php'); ?>
    <?php echo display_msg($msg); ?>

    <div class="flex justify-start mt-10">
        <div class="w-full sm:w-3/5 lg:w-3/5">
            <div class="bg-white shadow-md rounded-lg">
                <div class="custom-header p-10 border-b">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-2" style="font-size: 20px;"></i>
                        <strong class="text-3xl font-bold">EDIT PRODUCT</strong>
                    </div>
                </div>
                <div class="p-10"> <!-- Increased padding for more vertical space -->
                    <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" class="clearfix">
                        <div class="mb-8"> <!-- Increased bottom margin -->
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-th-large"></i>
                                </span>
                                <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-title" value="<?php echo remove_junk($product['name']); ?>" placeholder="Product Name" required>
                            </div>
                        </div>

                        <div class="mb-8 flex space-x-4"> <!-- Increased bottom margin -->

                            <!-- Product Category Dropdown -->
                            <select class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-category" required>
                                <?php foreach ($all_categories as $cat): ?>
                                    <option value="<?php echo (int)$cat['id'] ?>" <?php if ($cat['id'] === $product['category_id']) echo 'selected'; ?>>
                                        <?php echo $cat['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <!-- Product Photo Dropdown -->
                            <select class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-photo">
                                <?php foreach ($all_photo as $photo): ?>
                                    <option value="<?php echo (int)$photo['id'] ?>" <?php if ($photo['id'] === $product['media_id']) echo 'selected'; ?>>
                                        <?php echo $photo['file_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            </div>


                        <div class="mb-8 flex space-x-4"> <!-- Increased bottom margin -->
                            <input type="number" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>" placeholder="Product Quantity" required>
                            <input type="number" step="0.01" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="cost-price" value="<?php echo remove_junk($product['buy_price']); ?>" placeholder="Cost Price" required>
                            <input type="number" step="0.01" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="sale-price" value="<?php echo remove_junk($product['sale_price']); ?>" placeholder="Sale Price" required>
                        </div>

                        <div class="text-right">
                            <button type="submit" name="product" class="bg-blue-500 text-white px-6 py-4 rounded hover:bg-blue-600">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php include_once('layouts/footer.php'); ?>
</body>
</html>
