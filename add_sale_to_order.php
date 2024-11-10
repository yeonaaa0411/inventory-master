<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
} else {
    $session->msg("d", "Missing order id.");
    redirect('sales_by_order.php?id=' . $order_id, false);
}

if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id', 'order_id', 'quantity', 'sale_price');
    validate_fields($req_fields);
    if (empty($errors)) {
        $p_id = $db->escape((int)$_POST['s_id']);
        $o_id = $db->escape((int)$_POST['order_id']);
        $s_qty = $db->escape((int)$_POST['quantity']);
        $product = find_by_id("products", $p_id);

        if ((int)$product['quantity'] < $s_qty) {
            $session->msg('d', 'Insufficient Quantity for Sale!');
            redirect('sales_by_order.php?id=' . $order_id, false);
        }

        $s_price = $db->escape($_POST['sale_price']);
        $s_total = $s_qty * $s_price;
        $date = make_date();

        $sql = "INSERT INTO sales (product_id, order_id, qty, price, date)";
        $sql .= " VALUES ('{$p_id}', '{$o_id}', '{$s_qty}', '{$s_total}', '{$date}')";

        if ($db->query($sql)) {
            decrease_product_qty($s_qty, $p_id);
            $session->msg('s', "Sale added.");
            redirect('sales_by_order.php?id=' . $order_id, false);
        } else {
            $session->msg('d', 'Sorry, failed to add!');
            redirect('sales_by_order.php?id=' . $order_id, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('sales_by_order.php?id=' . $order_id, false);
    }
}

$all_categories = find_all('categories');

// Get products list sorted alphabetically by name, based on category filter
if (isset($_POST['update_category']) && !empty($_POST['product-category'])) {
    $category_id = (int)$_POST['product-category'];
    $products_available = find_products_by_category($category_id);
    // Order by name in the SQL query within the function or sort the array here
    usort($products_available, function ($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
} else {
    $products_available = join_product_table();
    // Sort the array by name in ascending order
    usort($products_available, function ($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
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

    <style>
        th, td {
            padding: 20px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        th {
            background-color: #eaf5e9;
        }

        table {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }

        tr:hover {
            background-color: #f7fafc;
        }

        .header-bg {
            background-color: #eaf5e9;
        }

        .quantity-input {
            width: 60px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center mt-10">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-1 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-6 header-bg border-b">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-th-list mr-2"></i>
                    Add Sales to Order #<?php echo $order_id; ?>
                </strong>
            </div>
            <div class="p-4">
                <form method="post" action="">
                    <div class="flex space-x-4">
                        <select class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="product-category">
                            <option value="">Select Product Category</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id'] ?>">
                                    <?php echo $cat['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_category" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Filter Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-4 py-2">Item</th>
                            <th class="text-center border px-4 py-2">Photo</th>
                            <th class="text-center border px-4 py-2">Price</th>
                            <th class="text-center border px-4 py-2">Quantity</th>
                            <th class="text-center border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="product_info">
                        <?php
                        $sales = find_sales_by_order_id($order_id);

                        // Ensure $sales is an array to prevent the "foreach" error
                        if (!$sales) {
                            $sales = array(); // Set $sales to an empty array if it is null or false
                        }

                        foreach ($products_available as $product) {
                            $added_to_order = false;
                            foreach ($sales as $sale) {
                                if ($product['name'] == $sale['name']) {
                                    $added_to_order = true;
                                }
                            }

                            if (!$added_to_order) {
                                ?>
                                <form method="post" action="add_sale_to_order.php?id=<?php echo $order_id; ?>">
                                    <tr>
                                        <td><?php echo $product['name']; ?></td>
                                        <td>
                                            <?php if ($product['media_id'] === '0'): ?>
                                                <img class="img-avatar img-circle mx-auto" src="uploads/products/no_image.jpg" alt="">
                                            <?php else: ?>
                                                <img class="img-avatar img-circle mx-auto" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                            <?php endif; ?>
                                        </td>
                                        <input type="hidden" name="s_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        <input type="hidden" class="form-control" name="sale_price" value="<?php echo $product['sale_price']; ?>">
                                        <td><?php echo $product['sale_price']; ?></td>
                                        <td class="flex justify-center">
                                            <input type="number" class="form-control text-center quantity-input" name="quantity" placeholder="Qty">
                                        </td>
                                        <td>
                                            <button type="submit" name="add_sale" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Sale</button>
                                        </td>
                                    </tr>
                                </form>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
