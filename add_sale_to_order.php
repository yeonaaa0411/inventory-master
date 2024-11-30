<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(2);

if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
} else {
    $session->msg("d", "Missing order ID.");
    redirect('sales_by_order.php', false);
}

if (isset($_POST['add_sale'])) {
    $req_fields = ['s_id', 'order_id', 'quantity', 'sale_price'];
    validate_fields($req_fields);
    if (empty($errors)) {
        $p_id = $db->escape((int)$_POST['s_id']);
        $o_id = $db->escape((int)$_POST['order_id']);
        $s_qty = $db->escape((int)$_POST['quantity']);
        $product = find_by_id("products", $p_id);

        if ((int)$product['quantity'] < $s_qty) {
            $session->msg('d', 'Insufficient Quantity for Sale!');
            redirect('add_sale_to_order.php?id=' . $order_id, false);
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
            $session->msg('d', 'Failed to add sale!');
            redirect('add_sale_to_order.php?id=' . $order_id, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale_to_order.php?id=' . $order_id, false);
    }
}

$products_available = join_product_table(); // Fetch all products
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .table-container {
            overflow-x: auto;
        }

        .custom-header {
            background-color: #d1fae5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: rgba(236, 253, 245, 1);
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .add-sale-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-sale-btn:hover {
            background-color: #45a049;
        }

        /* Increase the width of the quantity input */
        .quantity-input {
            width: 100px;
            text-align: center;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include_once('layouts/header.php'); ?>

    <div class="w-full px-4 py-6">
        <div class="bg-white p-6 rounded shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-center">Add Sale to Order #<?php echo $order_id; ?></h1>
            <?php echo display_msg($msg); ?>

            <!-- Search Bar -->
            <input type="text" id="search" placeholder="Search products..." class="search-input" onkeyup="filterProducts()">

            <!-- Product List Table -->
            <div class="table-container">
                <table>
                    <thead class="custom-header">
                        <tr>
                            <th class="text-center">Item</th>
                            <th class="text-center">Photo</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Stock</th> <!-- New Column for Stock -->
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="product_info">
                        <?php foreach ($products_available as $product): ?>
                            <tr class="product-row">
                                <td><?php echo $product['name']; ?></td>
                                <td>
                                    <img src="uploads/products/<?php echo $product['media_id'] == 0 ? 'no_image.jpg' : $product['image']; ?>" alt="" class="w-16 h-16 mx-auto">
                                </td>
                                <td><?php echo $product['sale_price']; ?></td>
                                <td><?php echo $product['quantity']; ?> <!-- Stock quantity --> </td> <!-- New column for stock -->
                                <form method="post" action="add_sale_to_order.php?id=<?php echo $order_id; ?>">
                                    <td>
                                        <input type="hidden" name="s_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        <input type="hidden" name="sale_price" value="<?php echo $product['sale_price']; ?>">
                                        <input type="number" name="quantity" placeholder="Qty" class="quantity-input border border-gray-300 rounded p-2">
                                    </td>
                                    <td>
                                        <button type="submit" name="add_sale" class="add-sale-btn">Add Sale</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterProducts() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const productName = row.querySelector('td:first-child').innerText.toLowerCase();
                row.style.display = productName.includes(searchInput) ? '' : 'none';
            });
        }
    </script>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
