<?php
$page_title = 'Daily Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$year = date('Y');
$month = date('m');
$day = date('d');
$today = date('Y-m-d'); // Format today's date as "YYYY-MM-DD"

// Fetch sales data
$sales = dailySales($year, $month) ?? [];

// Filter sales to include only those from today's date
$sales_today = array_filter($sales, function ($sale) use ($today) {
    return $sale['date'] === $today;
});

// Pagination variables
$limit = 50; // Limit the number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for pagination

// Slice array to simulate pagination on today's sales
$sales_for_page = array_slice($sales_today, $offset, $limit);
$total_sales = count($sales_today); // Total records for today
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
            <i class="fas fa-calendar-day mr-2"></i> Daily Sales Overview
        </h2>
        <div class="flex space-x-4">
            <!-- Toggle Buttons -->
            <a href="employee_daily_sales.php" class="px-6 py-3 rounded-full text-lg font-medium transition-colors duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'employee_daily_sales.php' ? 'bg-blue-700 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white'; ?>"> Daily Sales </a>
            <!-- Print Report Button -->
            <form action="sale_report_process.php" method="POST" target="_blank" class="inline-block">
                <input type="hidden" name="start-date" value="<?php echo date('Y-m-d'); ?>">
                <input type="hidden" name="end-date" value="<?php echo date('Y-m-d'); ?>">
                <button type="submit" name="submit" class="px-6 py-3 bg-blue-700 text-white rounded-full text-lg font-medium hover:bg-blue-800 transition-colors duration-200">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </form>
        </div>
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
                                <td colspan="5" class="text-center py-4 text-gray-500">No sales data available for today.</td>
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