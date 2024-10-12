<?php
$page_title = 'All Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(3);

$sales = find_all_sales();
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
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        th {
            background-color: #eaf5e9;
        }

        table {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }

        tr {
            min-height: 60px;
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
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-start items-center p-6 header-bg border-b">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-th-list mr-2"></i> <!-- Icon for sales -->
                    All Sales
                </strong>
            </div>
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-6 py-3" style="width: 60px;">#</th>
                            <th class="text-center border px-6 py-3" style="width: 15%;">Order</th>
                            <th class="text-center border px-6 py-3">Product Name</th>
                            <th class="text-center border px-6 py-3" style="width: 15%;">Quantity</th>
                            <th class="text-center border px-6 py-3" style="width: 15%;">Total</th>
                            <th class="text-center border px-6 py-3" style="width: 15%;">Date</th>
                            <th class="text-center border px-6 py-3" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                        <tr style="height: 80px;">
                            <td class="text-center"><?php echo count_id(); ?></td>
                            <td class="text-center"><?php echo (int)$sale['order_id']; ?></td>
                            <td class="text-center"><?php echo remove_junk($sale['name']); ?></td>
                            <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                            <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
