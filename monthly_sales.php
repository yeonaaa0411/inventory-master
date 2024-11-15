<?php 
$page_title = 'Monthly Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$year = date('Y');

// Fetch sales data from both daily and monthly sales functions
$sales = monthlySales($year);
$sales = $sales ?: []; // If $sales is null, set it to an empty array

// Fetch the daily sales for the current month and aggregate it
$daily_sales = dailySales($year, date('m'));
$daily_sales_aggregated = [];
foreach ($daily_sales as $sale) {
    // Ensure product_id exists before using it
    if (isset($sale['product_id']) && !empty($sale['product_id'])) {
        $product_id = $sale['product_id'];
        if (!isset($daily_sales_aggregated[$product_id])) {
            $daily_sales_aggregated[$product_id] = [
                'qty' => 0,
                'total_saleing_price' => 0
            ];
        }
        $daily_sales_aggregated[$product_id]['qty'] += $sale['qty'];
        $daily_sales_aggregated[$product_id]['total_saleing_price'] += $sale['total_saleing_price'];
    } else {
        // Log or handle missing product_id
        error_log("Missing product_id in daily sales: " . json_encode($sale));
    }
}

// Merge daily sales data into the monthly sales data
foreach ($sales as &$sale) {
    // Ensure product_id exists before processing
    if (isset($sale['product_id']) && !empty($sale['product_id'])) {
        $product_id = $sale['product_id'];
        if (isset($daily_sales_aggregated[$product_id])) {
            $sale['qty'] += $daily_sales_aggregated[$product_id]['qty'];
            $sale['total_saleing_price'] += $daily_sales_aggregated[$product_id]['total_saleing_price'];
        }
    } else {
        // Log or handle missing product_id
        error_log("Missing product_id in monthly sales: " . json_encode($sale));
    }
}

// Pagination variables
$limit = 50; // Limit the number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for pagination

// Slice array to simulate pagination
$sales_for_page = array_slice($sales, $offset, $limit);
$total_sales = count($sales); // Total records
$total_pages = ceil($total_sales / $limit); // Total pages
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
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
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
                    <i class="fas fa-chart-line mr-2"></i> Monthly Sales
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
                        <?php if (!empty($sales_for_page)): ?>
                            <?php foreach ($sales_for_page as $sale): ?>
                                <tr>
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td class="text-center"><?php echo remove_junk($sale['name']); ?></td>
                                    <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                                    <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
                                    <td class="text-center"><?php echo $sale['date']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-black-500">No sales data available for this month.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Pagination Controls -->
    <div class="flex justify-center mt-4 mb-6">
        <!-- Previous Button -->
        <a href="?page=<?php echo max(1, $page - 1); ?>"
           class="mr-4 px-4 py-2 bg-gray-300 text-gray-700 rounded <?php echo $page <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>"
           <?php echo $page <= 1 ? 'aria-disabled="true"' : ''; ?>>
            Previous
        </a>
        <!-- Next Button -->
        <a href="?page=<?php echo min($total_pages, $page + 1); ?>"
           class="px-4 py-2 bg-gray-300 text-gray-700 rounded <?php echo $page >= $total_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>"
           <?php echo $page >= $total_pages ? 'aria-disabled="true"' : ''; ?>>
            Next
        </a>
    </div>
    <?php include_once('layouts/footer.php'); ?>
</body>
</html>
