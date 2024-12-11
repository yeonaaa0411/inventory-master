<?php
$page_title = 'Sales Overview';
require_once('includes/load.php');
page_require_level(3);

$view = isset($_GET['view']) ? $_GET['view'] : 'all'; // Default view is 'all'

// Determine the content based on the view parameter
switch ($view) {
    case 'daily':
        $page_title = 'Daily Sales';
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $today = date('Y-m-d');
        $sales = dailySales($year, $month) ?? [];
        $sales = array_filter($sales, function ($sale) use ($today) {
            return $sale['date'] === $today;
        });
        break;

    case 'monthly':
        $page_title = 'Monthly Sales';
        $year = date('Y');
        $month = date('m');
        $sales = monthlySales($year, $month) ?? [];
        break;

    case 'report': // New case for Sales Report
        $page_title = 'Generate Sales Report';
        // Include the report generation code here
        break;

    case 'all':
    default:
        $page_title = 'All Sales';
        $sales = find_all_sales() ?? [];
        break;
}

// Pagination logic
$limit = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sales_for_page = array_slice($sales, $offset, $limit);
$total_sales = count($sales);
$total_pages = ceil($total_sales / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        th, td { padding: 1.25rem; }
        table { border-collapse: collapse; width: 100%; }
        .active-tab { background-color: #3182CE; color: white; }
        .tab-btn { padding: 0.5rem 1rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #E2E8F0; color: #4A5568; }
        .tab-btn:hover { background-color: #CBD5E0; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include_once('layouts/header.php'); ?>

    <div class="w-full px-4 py-6">
        <?php echo display_msg($msg); ?>

        <!-- Tab Navigation -->
        <div class="mb-4">
            <a href="?view=all" class="tab-btn <?php echo $view === 'all' ? 'active-tab' : ''; ?>">All Sales</a>

            <a href="?view=monthly" class="tab-btn <?php echo $view === 'monthly' ? 'active-tab' : ''; ?>">Monthly Sales</a>
            <a href="?view=report" class="tab-btn <?php echo $view === 'report' ? 'active-tab' : ''; ?>">Sales Report</a> <!-- New button -->
        </div>

        <!-- Content based on selected view -->
        <?php if ($view === 'report'): ?>
            <!-- Display the Sales Report form/content -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-6 bg-green-50">
                    <h2 class="text-2xl font-semibold text-gray-800"><?php echo $page_title; ?></h2>
                </div>
                <div class="overflow-x-auto px-6 py-4">
                    <form method="post" action="sale_report_process.php">
                        <div class="mb-6">
                            <label for="start-date" class="form-label">Start Date</label>
                            <input type="date" id="start-date" class="form-input" name="start-date" required>
                        </div>

                        <div class="mb-6">
                            <label for="end-date" class="form-label">End Date</label>
                            <input type="date" id="end-date" class="form-input" name="end-date" required>
                        </div>

                        <div class="flex justify-center mt-6">
                            <button type="submit" name="submit" class="btn-primary">Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Display the Sales Data Table -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-6 bg-green-50">
                    <h2 class="text-2xl font-semibold text-gray-800"><?php echo $page_title; ?></h2>
                </div>
                <div class="overflow-x-auto px-6 py-4">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="border-b bg-gray-100">
                                <th class="text-center px-4 py-2 font-medium text-gray-600">#</th>
                                <th class="text-center px-4 py-2 font-medium text-gray-600">Product Name</th>
                                <th class="text-center px-4 py-2 font-medium text-gray-600">Quantity Sold</th>
                                <th class="text-center px-4 py-2 font-medium text-gray-600">Total Sales</th>
                                <th class="text-center px-4 py-2 font-medium text-gray-600">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sales_for_page)): ?>
                                <?php foreach ($sales_for_page as $sale): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="text-center px-4 py-2"><?php echo count_id(); ?></td>
                                        <td class="text-center px-4 py-2"><?php echo remove_junk($sale['name']); ?></td>
                                        <td class="text-center px-4 py-2"><?php echo (int)$sale['qty']; ?></td>
                                        <td class="text-center px-4 py-2"><?php echo remove_junk($sale['price']); ?></td>
                                        <td class="text-center px-4 py-2"><?php echo $sale['date']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">No sales records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($view !== 'report'): ?>
            <div class="flex justify-center mt-4 mb-6">
                <a href="?view=<?php echo $view; ?>&page=1" class="tab-btn">First</a>
                <a href="?view=<?php echo $view; ?>&page=<?php echo max(1, $page - 1); ?>" class="tab-btn">Previous</a>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?view=<?php echo $view; ?>&page=<?php echo $i; ?>" class="tab-btn <?php echo $page === $i ? 'active-tab' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <a href="?view=<?php echo $view; ?>&page=<?php echo min($total_pages, $page + 1); ?>" class="tab-btn">Next</a>
                <a href="?view=<?php echo $view; ?>&page=<?php echo $total_pages; ?>" class="tab-btn">Last</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>
</html>
