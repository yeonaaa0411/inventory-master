<?php
$page_title = 'Daily Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$year = date('Y');
$month = date('m');
$sales = dailySales($year, $month);
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
        .table-row-height th,
        .table-row-height td {
            padding-top: 1.5rem; /* Increase padding for more row height */
            padding-bottom: 1.5rem; /* Increase padding for more row height */
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

        .header-bg {
            background-color: #eaf5e9;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center mt-6">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-4 header-bg rounded-t-lg">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-calendar-day mr-2"></i> <!-- Icon for daily sales -->
                    Daily Sales
                </strong>
            </div>
            <div class="p-4">
                <table class="min-w-full border-collapse table-row-height">
                    <thead>
                        <tr>
                            <th class="text-center border px-4 py-2" style="width: 50px;">#</th>
                            <th class="text-center border px-4 py-2">Product Name</th>
                            <th class="text-center border px-4 py-2" style="width: 15%;">Quantity Sold</th>
                            <th class="text-center border px-4 py-2" style="width: 15%;">Total</th>
                            <th class="text-center border px-4 py-2" style="width: 15%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td class="text-center"><?php echo count_id(); ?></td>
                            <td class="text-center"><?php echo remove_junk($sale['name']); ?></td>
                            <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                            <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
                            <td class="text-center"><?php echo $sale['date']; ?></td>
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
