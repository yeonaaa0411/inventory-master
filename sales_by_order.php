<?php
$page_title = 'All Sales by Order';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

if (isset($_GET['id'])) {
    $order_id = (int) $_GET['id'];
} else {
    $session->msg("d", "Missing order id.");
}

$sales = find_sales_by_order_id($order_id);
$order = find_by_id("orders", $order_id);
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
</head>

<body class="bg-gray-100">
    <!-- Include header -->
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center mt-10">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-6 header-bg border-b">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-box mr-2"></i>
                    Order #<?php echo $order_id; ?>
                </strong>
            </div>
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-4 py-2">#</th>
                            <th class="text-center border px-4 py-2">Customer</th>
                            <th class="text-center border px-4 py-2">Pay Method</th>
                            <th class="text-center border px-4 py-2">Notes</th>
                            <th class="text-center border px-4 py-2">Date</th>
                            <th class="text-center border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="h-16"> <!-- Add height class to increase row height -->
                            <td class="text-center"><?php echo $order['id']; ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['customer'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['paymethod'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['notes'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['date'])); ?></td>
                            <td class="text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="edit_order.php?id=<?php echo (int)$order['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="delete_order.php?id=<?php echo (int)$order['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-6 header-bg border-b">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-box mr-2"></i>
                    Sales
                </strong>
                <a href="add_sale_to_order.php?id=<?php echo $order_id; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Sale</a>
            </div>
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-4 py-2">#</th>
                            <th class="text-center border px-4 py-2">Product Name</th>
                            <th class="text-center border px-4 py-2">Quantity</th>
                            <th class="text-center border px-4 py-2">Total</th>
                            <th class="text-center border px-4 py-2">Date</th>
                            <th class="text-center border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr class="h-16"> <!-- Add height class to increase row height -->
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td class="text-center"><?php echo remove_junk($sale['name']); ?></td>
                                <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                                <td class="text-center">₱<?php echo number_format($sale['price'], 2); ?></td>
                                <td class="text-center"><?php echo $sale['date']; ?></td>
                                <td class="text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="delete_sale.php?id=<?php echo (int)$sale['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Display Total Row -->
                        <tr class="h-16"> <!-- Add height class to increase row height -->
                            <td colspan="3"></td>
                            <td class="text-center font-bold">Total: ₱<?php echo number_format(array_sum(array_column($sales, 'price')), 2); ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
