<?php
$page_title = 'All Stock';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

// Fetch all stock and products
$all_stock = find_all('stock');
$all_products = find_all('products');

// Pagination variables
$limit = 50; // Limit the number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for pagination

// Slice array to simulate pagination
$stock_for_page = array_slice($all_stock, $offset, $limit);
$total_stock = count($all_stock); // Total records
$total_pages = ceil($total_stock / $limit); // Total pages
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
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        th {
            background-color: rgba(236, 253, 245, 1); /* .bg-green-50 */
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .header-bg {
            background-color: rgba(236, 253, 245, 1); /* .bg-green-50 */
        }

        .table-row-height th, .table-row-height td {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include_once('layouts/header.php'); ?>
    
    <div class="flex justify-center">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-6 header-bg border-b">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-box mr-2"></i>
                    Inventory Log
                </strong>
            </div>
            <div class="p-4">
                <table class="min-w-full table-auto border-collapse table-row-height">
                    <thead>
                        <tr class="border-b header-bg">
                            <th class="text-center px-4 py-2 font-medium text-gray-600">#</th>
                            <th class="text-center px-4 py-2 font-medium text-gray-600">Product</th>
                            <th class="text-center px-4 py-2 font-medium text-gray-600">Quantity</th>
                            <th class="text-center px-4 py-2 font-medium text-gray-600">Comments</th>
                            <th class="text-center px-4 py-2 font-medium text-gray-600">Date</th>
                            <th class="text-center px-4 py-2 font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($stock_for_page) && count($stock_for_page) > 0): ?>
                            <?php foreach ($stock_for_page as $index => $stock): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="text-center px-4 py-3"><?php echo ($index + 1) + (($page - 1) * $limit); ?></td>
                                    <td class="text-center px-4 py-3">
                                        <a href="view_product.php?id=<?php echo (int)$stock['product_id']; ?>">
                                            <?php
                                            foreach ($all_products as $product) {
                                                if ($stock['product_id'] == $product['id']) {
                                                    echo remove_junk($product['name']);
                                                }
                                            }
                                            ?>
                                        </a>
                                    </td>
                                    <td class="text-center px-4 py-3"><?php echo remove_junk(ucfirst($stock['quantity'])); ?></td>
                                    <td class="text-center px-4 py-3"><?php echo remove_junk(ucfirst($stock['comments'])); ?></td>
                                    <td class="text-center px-4 py-3"><?php echo remove_junk(ucfirst($stock['date'])); ?></td>
                                    <td class="text-center px-4 py-3">
                                        <div class="flex justify-center space-x-2">
                                            <a href="edit_stock.php?id=<?php echo (int)$stock['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">No stock records found.</td>
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
