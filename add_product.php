<?php
$page_title = 'Add Product';
require_once('includes/load.php');
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
                $session->msg('d', ' Sorry failed to add!');
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
            $session->msg('d', ' Sorry failed to add!');
            redirect('add_product.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
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
        th,
        td {
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

    <div class="flex justify-start mt-10">
    <div class="w-full sm:w-3/5 lg:w-3/5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="custom-header p-10 border-b">
                <div class="flex items-center">
                    <i class="fas fa-box mr-2" style="font-size: 20px;"></i>
                    <strong class="text-3xl font-bold">ADD NEW PRODUCT</strong>
                </div>
            </div>
            <div class="p-10"> <!-- Increased padding for more vertical space -->
                <?php echo display_msg($msg); ?>
                <form method="post" action="add_product.php" class="clearfix">
                    <div class="mb-8"> <!-- Increased bottom margin -->
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-th-large"></i>
                            </span>
                            <!-- Increase vertical padding -->
                            <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-title" placeholder="Product Name" required>
                        </div>
                    </div>

                    <div class="mb-8 flex space-x-4"> <!-- Increased bottom margin -->
                        <select class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-category" required>
                            <option value="">Select Product Category</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id'] ?>">
                                    <?php echo $cat['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-photo">
                            <option value="">Select Product Photo</option>
                            <?php foreach ($all_photo as $photo): ?>
                                <option value="<?php echo (int)$photo['id'] ?>">
                                    <?php echo $photo['file_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-8 flex space-x-4"> <!-- Increased bottom margin -->
                        <input type="number" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product-quantity" placeholder="Product Quantity" required>

                        <input type="number" step="0.01" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="cost-price" placeholder="Cost Price" required>

                        <input type="number" step="0.01" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="sale-price" placeholder="Sale Price" required>
                    </div>

                    <div class="text-right">
                        <button type="submit" name="add_product" class="bg-blue-500 text-white px-6 py-4 rounded hover:bg-blue-600">Add Product</button>
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
