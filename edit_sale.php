<?php
$page_title = 'Edit Sale';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$sale = find_by_id('sales', (int)$_GET['id']);
if (!$sale) {
    $session->msg("d", "Missing sale id.");
    redirect('sales.php');
}

$product = find_by_id('products', $sale['product_id']);
$order = find_by_id('orders', $sale['order_id']);

// Get the referrer URL to return after updating
$referrer_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'sales.php';

if (isset($_POST['update_sale'])) {
    $req_fields = array('title', 'order_id', 'quantity', 'date');
    validate_fields($req_fields);
    if (empty($errors)) {
        $o_id = $db->escape((int)$_POST['order_id']);
        $p_id = $db->escape((int)$product['id']);
        $quantity = $db->escape((int)$_POST['quantity']);
        $s_qty_diff = 0;

        if ($quantity != $sale['qty']) {
            if ($quantity > $sale['qty']) {
                $s_qty_diff = $quantity - $sale['qty'];
                if ((int)$product['quantity'] < $s_qty_diff) {
                    $session->msg('d', 'Insufficient Quantity for Sale!');
                    redirect('edit_sale.php?id=' . (int)$sale['id'], false);
                } else {
                    $decrease_quantity_flag = true;
                }
            } else if ($quantity < $sale['qty']) {
                $s_qty_diff = $sale['qty'] - $quantity;
                $decrease_quantity_flag = false;
            }
        }

        // Calculate the new total based on the updated quantity and product unit price
        $unit_price = $product['sale_price'];
        $s_total = $unit_price * $quantity;
        $s_total = $db->escape($s_total);

        $date = $db->escape($_POST['date']);
        $s_date = date("Y-m-d", strtotime($date));

        $sql = "UPDATE sales SET order_id='{$o_id}', product_id='{$p_id}', qty={$quantity}, price='{$s_total}', date='{$s_date}' WHERE id='{$sale['id']}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            if ($s_qty_diff > 0) {
                if ($decrease_quantity_flag) {
                    decrease_product_qty($s_qty_diff, $p_id);
                } else {
                    increase_product_qty($s_qty_diff, $p_id);
                }
            }
            $session->msg('s', "Sale updated.");
            // Redirect to the order page after the update
            redirect('sales_by_order.php?id=' . (int)$order['id'], false); // Redirect to the order's page
        } else {
            $session->msg('d', 'No changes made to the sales!');
            redirect('edit_sale.php?id=' . (int)$sale['id'], false); // Stay on the editing page if update failed
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_sale.php?id=' . (int)$sale['id'], false);
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
    </style>

    <script>
        // Function to update the total price based on quantity input
        function updateTotal() {
            var quantity = document.querySelector('input[name="quantity"]').value;
            var unitPrice = <?php echo $product['sale_price']; ?>;
            var total = quantity * unitPrice;
            document.querySelector('input[name="total"]').value = total.toFixed(2);
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-6 header-bg border-b">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Sale
                </strong>
                <div class="pull-right">
                    <a href="sales.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Show All Sales</a>
                </div>
            </div>
            <div class="p-4">
                <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="text-center border px-4 py-2">Order #</th>
                                <th class="text-center border px-4 py-2">Product Title</th>
                                <th class="text-center border px-4 py-2">Quantity</th>
                                <th class="text-center border px-4 py-2">Price</th>
                                <th class="text-center border px-4 py-2">Total</th>
                                <th class="text-center border px-4 py-2">Date</th>
                                <th class="text-center border px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="order_id" value="<?php echo remove_junk($order['id']); ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="quantity" value="<?php echo (int)$sale['qty']; ?>" min="1" required onchange="updateTotal()">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="price" value="<?php echo remove_junk($product['sale_price']); ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="total" value="<?php echo remove_junk($sale['price']); ?>" readonly>
                                </td>
                                <td>
                                    <input type="date" class="form-control" name="date" value="<?php echo remove_junk($sale['date']); ?>" required>
                                </td>
                                <td>
                                    <button type="submit" name="update_sale" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
