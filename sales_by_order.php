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
$total_price = array_sum(array_column($sales, 'price')); // Calculate total price
$change = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment'])) {
    $payment = (float) $_POST['payment'];
    $change = $payment - $total_price; // Calculate change
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
            background-color: rgba(236, 253, 245, 1); /* Applying bg-green-50 color */
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
            background-color: rgba(236, 253, 245, 1); /* Applying bg-green-50 color */
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
                <a href="add_order.php" class="bg-blue-500 text-white px-10 py-2 rounded hover:bg-blue-600">Back</a>
            </div>
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                        <th class="text-center border px-4 py-2 bg-green-50">#</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Customer</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Payment Method</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Notes</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Date</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Actions</th> <!-- Applying bg-green-50 -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="h-16">
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo remove_junk(ucfirst($order['customer'])); ?></td>
                            <td><?php echo remove_junk(ucfirst($order['paymethod'])); ?></td>
                            <td><?php echo remove_junk(ucfirst($order['notes'])); ?></td>
                            <td><?php echo remove_junk(ucfirst($order['date'])); ?></td>
                            <td>
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
                        <th class="text-center border px-4 py-2 bg-green-50">#</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Product Name</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Quantity</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Total</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Date</th> <!-- Applying bg-green-50 -->
                            <th class="text-center border px-4 py-2 bg-green-50">Actions</th> <!-- Applying bg-green-50 -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr class="h-16">
                                <td><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk($sale['name']); ?></td>
                                <td><?php echo (int)$sale['qty']; ?></td>
                                <td>₱<?php echo number_format($sale['price'], 2); ?></td>
                                <td><?php echo $sale['date']; ?></td>
                                <td>
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

                        <tr class="h-16">
                            <td colspan="3"></td>
                            <td class="text-center font-bold">Total: ₱<?php echo number_format($total_price, 2); ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Payment and Change Section -->
                <div class="mt-5 flex justify-center items-center flex-col">
    <form class="flex flex-col items-center space-y-4">
        <div class="flex space-x-4 items-center">
            <label for="payment" class="font-bold">Payment (₱):</label>
            <input type="number" id="payment" name="payment" step="0.01" class="border border-gray-300 p-2 rounded w-48" required>
        </div>
    </form>
    <div id="change-display" class="mt-6 text-xl font-bold text-center">
        Change: ₱0.00
    </div>
</div>

<script>
    // JavaScript to calculate change automatically
    document.getElementById('payment').addEventListener('input', function () {
        const totalPrice = <?php echo $total_price; ?>; // Get the total price from PHP
        const payment = parseFloat(this.value) || 0; // Get the entered payment or 0
        const change = payment - totalPrice; // Calculate change
        const changeDisplay = document.getElementById('change-display');
        changeDisplay.textContent = `Change: ₱${change.toFixed(2)}`; // Update the change display
    });
</script>


            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
