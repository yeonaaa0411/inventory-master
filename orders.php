<?php
$page_title = 'All Orders';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$all_orders = find_all('orders');
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
        th,
        td {
            padding: 30px; /* Increase padding for more space */
            border: 1px solid #e2e8f0;
        }

        th {
            background-color: #eaf5e9;
        }

        table {
            border-collapse: separate; /* Changed from collapse to separate */
            border-spacing: 0 10px; /* Added spacing between rows */
            width: 100%;
        }

        tr {
            min-height: 60px; /* Set a minimum height for each row */
        }

        tr:hover {
            background-color: #f7fafc;
        }

        .header-bg {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
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
            <div class="flex justify-between items-center p-6 header-bg border-b"> <!-- Added header-bg class and increased padding to p-6 -->
                <strong class="text-3xl font-bold">
                    <i class="fas fa-th mr-2"></i> <!-- Icon for orders -->
                    All Orders
                </strong>
            </div>
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-6 py-3" style="width: 60px;">#</th>
                            <th class="text-center border px-6 py-3" style="width: 20%;">Customer</th>
                            <th class="text-center border px-6 py-3" style="width: 15%;">Pay Method</th>
                            <th class="text-center border px-6 py-3" style="width: 25%;">Notes</th>
                            <th class="text-center border px-6 py-3" style="width: 20%;">Date</th>
                            <th class="text-center border px-6 py-3" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_orders as $order): ?>
                        <tr style="height: 80px;">
                            <td class="text-center">
                                <a href="sales_by_order.php?id=<?php echo (int)$order['id']; ?>">
                                    <?php echo (int)$order['id']; ?>
                                </a>
                            </td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['customer'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['paymethod'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['notes'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['date'])); ?></td>
                            <td class="text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="edit_order.php?id=<?php echo (int)$order['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="delete_order.php?id=<?php echo (int)$order['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Remove">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
