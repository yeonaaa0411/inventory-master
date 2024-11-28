<?php
$page_title = 'All Sales';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(3);

$sales = find_all_sales();

// Pagination variables
$sales = find_all_sales() ?? []; // Ensure the sales data is available
$limit = 50; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for pagination

// Slice the array to simulate pagination
$sales_for_page = array_slice($sales, $offset, $limit);
$total_sales = count($sales); // Total records
$total_pages = ceil($total_sales / $limit); // Total pages

// Calculate page range for pagination (max 3 pages visible)
$start_page = max(1, $page - 1); // Start from current page minus 1
$end_page = min($total_pages, $page + 1); // End at current page plus 1

if ($page == 1) {
    $end_page = min($total_pages, 3); // Show next 3 pages from the start
} elseif ($page == $total_pages) {
    $start_page = max(1, $total_pages - 2); // Show last 3 pages when on the last page
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
            padding: 1.25rem;
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

        .table-row-height th, .table-row-height td {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .pagination-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            background-color: #E2E8F0;
            color: #4A5568;
            margin-right: 0.5rem;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .pagination-btn:hover {
            background-color: #CBD5E0;
        }

        .pagination-btn.active {
            background-color: #3182CE;
            color: white;
        }

        .pagination-btn.disabled {
            background-color: #E2E8F0;
            color: #A0AEC0;
            cursor: not-allowed;
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
                    <i class="fas fa-th-list mr-2"></i> All Sales
                </h2>
            </div>
            <div class="overflow-x-auto px-6 py-4">
                <table class="min-w-full table-auto border-collapse table-row-height">
                    <thead>
                        <tr class="border-b bg-gray-100">
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">#</th>
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">Order</th>
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">Product Name</th>
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">Quantity</th>
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">Total</th>
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">Date</th>
                            <th class="text-center px-6 py-3 font-medium text-gray-600 bg-green-50">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sales_for_page)): ?>
                            <?php foreach ($sales_for_page as $sale): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="text-center px-4 py-3"><?php echo count_id(); ?></td>
                                <td class="text-center px-4 py-3"><?php echo (int)$sale['order_id']; ?></td>
                                <td class="text-center px-4 py-3"><?php echo remove_junk($sale['name']); ?></td>
                                <td class="text-center px-4 py-3"><?php echo (int)$sale['qty']; ?></td>
                                <td class="text-center px-4 py-3"><?php echo remove_junk($sale['price']); ?></td>
                                <td class="text-center px-4 py-3"><?php echo $sale['date']; ?></td>
                                <td class="text-center px-4 py-3">
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
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">No sales records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
<!-- Pagination Controls -->
<div class="flex justify-center mt-4 mb-6">

    <!-- Go to First Page Button -->
    <?php if ($page != 1): ?>
        <a href="?page=1"
           class="pagination-btn"
           title="Go to First Page">
            First Page
        </a>
    <?php endif; ?>
    
    <!-- Previous Button -->
    <a href="?page=<?php echo max(1, $page - 1); ?>"
       class="pagination-btn <?php echo $page <= 1 ? 'disabled' : ''; ?>"
       <?php echo $page <= 1 ? 'aria-disabled="true"' : ''; ?>>
        Previous
    </a>

    <!-- Display Page Numbers (Max 3 Pages) -->
    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
        <a href="?page=<?php echo $i; ?>"
           class="pagination-btn <?php echo $page == $i ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>


        <!-- Next Button -->
    <a href="?page=<?php echo min($total_pages, $page + 1); ?>"
       class="pagination-btn <?php echo $page >= $total_pages ? 'disabled' : ''; ?>"
       <?php echo $page >= $total_pages ? 'aria-disabled="true"' : ''; ?>>
        Next
    </a>
    <!-- Last Page Button -->
    <a href="?page=<?php echo $total_pages; ?>"
       class="pagination-btn <?php echo $page == $total_pages ? 'disabled' : ''; ?>"
       <?php echo $page == $total_pages ? 'aria-disabled="true"' : ''; ?>>
        Last
    </a>




</div>


    <?php include_once('layouts/footer.php'); ?>
</body>
</html>
