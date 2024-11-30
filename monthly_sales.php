<?php 
$page_title = 'Monthly Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$year = date('Y');
$current_month = date('m');

// Fetch sales data from both daily and monthly sales functions
$sales = monthlySales($year);
$sales = $sales ?: []; // If $sales is null, set it to an empty array

// Fetch the daily sales for the current month and aggregate it
$daily_sales = dailySales($year, $current_month);
$daily_sales_aggregated = [];
foreach ($daily_sales as $sale) {
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
        error_log("Missing product_id in daily sales: " . json_encode($sale));
    }
}

// Merge daily sales data into the monthly sales data
foreach ($sales as &$sale) {
    if (isset($sale['product_id']) && !empty($sale['product_id'])) {
        $product_id = $sale['product_id'];
        if (isset($daily_sales_aggregated[$product_id])) {
            $sale['qty'] += $daily_sales_aggregated[$product_id]['qty'];
            $sale['total_saleing_price'] += $daily_sales_aggregated[$product_id]['total_saleing_price'];
        }
    } else {
        error_log("Missing product_id in monthly sales: " . json_encode($sale));
    }
}

// Filter sales to only include those for the current month
$sales = array_filter($sales, function($sale) use ($current_month) {
    return date('m', strtotime($sale['date'])) == $current_month;
});

// Sort monthly sales by date in descending order
usort($sales, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .table-row-height th,
        .table-row-height td {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }
        th {
            background-color: #f4fafb;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .header-bg {
            background-color: #f4fafb;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include_once('layouts/header.php'); ?>
    <div class="w-full px-4 py-6">

        <?php echo display_msg($msg); ?>
        
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-6 bg-green-50">
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-chart-line mr-2"></i> Monthly Sales Overview
                </h2>
            </div>
            <div class="overflow-x-auto px-6 py-4">
                <table class="min-w-full table-auto border-collapse table-row-height">
                <thead>
    <tr class="border-b bg-gray-100">
        <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">#</th>
        <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Product Name</th>
        <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Quantity Sold</th>
        <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Total Sales</th>
        <th class="text-center px-4 py-2 font-medium text-gray-600 bg-green-50">Date</th>
    </tr>
</thead>

                    <tbody>
                        <?php if (!empty($sales_for_page)): ?>
                            <?php foreach ($sales_for_page as $index => $sale): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="text-center px-4 py-3"><?php echo ($index + 1) + (($page - 1) * $limit); ?></td>
                                    <td class="text-center px-4 py-3"><?php echo remove_junk($sale['name']); ?></td>
                                    <td class="text-center px-4 py-3"><?php echo (int)$sale['qty']; ?></td>
                                    <td class="text-center px-4 py-3"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
                                    <td class="text-center px-4 py-3"><?php echo date('M j, Y', strtotime($sale['date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">No sales data available for this month.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="flex justify-center items-center px-6 py-4 bg-gray-100 space-x-4">
                <!-- Previous Button -->
                <a href="?page=<?php echo max(1, $page - 1); ?>"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 <?php echo $page <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                   <?php echo $page <= 1 ? 'aria-disabled="true"' : ''; ?>>
                    <i class="fas fa-chevron-left"></i> Previous
                </a>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 <?php echo $page == $i ? 'bg-blue-500 text-white' : ''; ?>">
                       <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Button -->
                <a href="?page=<?php echo min($total_pages, $page + 1); ?>"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 <?php echo $page >= $total_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                   <?php echo $page >= $total_pages ? 'aria-disabled="true"' : ''; ?>>
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>
</html>
