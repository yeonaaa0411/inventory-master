<?php
$page_title = 'All Product';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

// Initialize products as an empty array
$products = [];

// Handle the search query (if any)
$searchQuery = isset($_GET['searchInput']) ? $_GET['searchInput'] : '';
if ($searchQuery != '') {
    $products = search_products($searchQuery);  // Function to search products by name
} else {
    $products = join_product_table();  // Get all products if no search query
}

// Sort the products alphabetically by name
usort($products, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

// Pagination logic
$limit = 100; // Products per page
$totalProducts = count($products); // Total number of products
$totalPages = ceil($totalProducts / $limit); // Calculate total pages
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page (default is 1)
$offset = ($page - 1) * $limit; // Calculate the offset for SQL query

// Slice the products array to show only the products for the current page
$products = array_slice($products, $offset, $limit);

// Fetch distinct categories
$categories = find_all_categories();  // Add a function to get categories if not already existing
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Handle the search and category filter
if ($searchQuery != '' && $selectedCategory != '') {
    $products = search_products_by_category($searchQuery, $selectedCategory);
} elseif ($searchQuery != '') {
    $products = search_products($searchQuery);
} elseif ($selectedCategory != '') {
    $products = filter_products_by_category($selectedCategory);
} else {
    $products = join_product_table();
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
        th,
        td {
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        th {
            background-color: rgba(236, 253, 245, 1); /* Applying bg-green-50 */
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr:hover {
            background-color: #f7fafc;
        }

        .header-bg {
            background-color: rgba(236, 253, 245, 1); /* bg-green-50 applied */
        }
    </style>

    <script>
        function filterProducts() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const tableRows = document.querySelectorAll('#productTable tbody tr');

            tableRows.forEach(row => {
                const productName = row.querySelector('.product-name').textContent.toLowerCase();
                if (productName.includes(searchInput)) {
                    row.style.display = '';  // Show row
                } else {
                    row.style.display = 'none';  // Hide row
                }
            });
        }
    </script>
</head>

<body class="bg-gray-100">
    <!-- Include header -->
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center">
        <div class="w-11/12 md:w-2/3">
            <?php echo display_msg($msg); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-4 header-bg">
                <strong class="text-3xl font-bold">
                    <i class="fas fa-box mr-2"></i>
                    <?php echo "All Products"; ?>
                </strong>
            </div>
            <div class="p-4">
                <!-- Search Input -->
                <div class="mb-4 flex items-center space-x-4">
                    <input type="text" id="searchInput" name="searchInput" class="border border-gray-300 rounded-md px-4 py-2 w-full"
                        placeholder="Search by product name..." value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>"
                        onkeyup="filterProducts()">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Search
                    </button>
                </div>
            </div>


    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-4">
                <!-- Category Filter (Positioned to the right) -->
                <form method="GET" action="" class="mb-4 flex justify-end">
                    <div class="flex items-center space-x-4">
                        <label for="category" class="mr-2 font-semibold">Filter by Category:</label>
                        <select id="category" name="category" class="border border-gray-300 rounded-md px-4 py-2 w-70">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($selectedCategory == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo remove_junk($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Filter
                        </button>
                    </div>
                </form>

                <!-- Table -->
                <table id="productTable" class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-4 py-2">#</th>
                            <th class="text-center border px-4 py-2">Category</th>
                            <th class="text-center border px-4 py-2">Product Name</th>
                            <th class="text-center border px-4 py-2">Photo</th>
                            <th class="text-center border px-4 py-2">Available Stock</th>
                            <th class="text-center border px-4 py-2">Cost Price</th>
                            <th class="text-center border px-4 py-2">Sale Price</th>
                            <th class="text-center border px-4 py-2">Product Added</th>
                            <th class="text-center border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td class="text-center"><?php echo remove_junk($product['category']); ?></td>
                                    <td class="text-center product-name">
                                        <a href="view_product.php?id=<?php echo (int)$product['id']; ?>&searchInput=<?php echo urlencode($product['name']); ?>">
                                            <?php echo $product['name']; ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center">
                                            <?php if ($product['media_id'] === '0'): ?>
                                                <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                                            <?php else: ?>
                                                <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt=""/>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center" style="<?php echo ($product['quantity'] == 0) ? 'color: red;' : ''; ?>">
                                        <?php echo remove_junk($product['quantity']); ?>
                                    </td>
                                    <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                                    <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                    <td class="text-center"><?php echo read_date($product['date']); ?></td>
<td class="text-center">
    <div class="flex justify-center space-x-2">
        <!-- Add Stock Button with Icon -->
        <a href="add_stock.php?id=<?php echo (int)$product['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Add">
            <i class="fas fa-plus"></i>
        </a>
        
        <!-- Edit Button with Icon -->
        <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600" title="Edit">
            <i class="fas fa-pencil-alt"></i>
        </a>
        
        <!-- Delete Button with Icon -->
        <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </a>
    </div>
</td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="9">No product found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        <nav>
            <ul class="flex space-x-4">
                <li>
                    <a href="?page=1" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">First</a>
                </li>
                <li>
                    <a href="?page=<?php echo max(1, $page - 1); ?>" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Prev</a>
                </li>
                <li>
                    <a href="?page=<?php echo min($totalPages, $page + 1); ?>" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Next</a>
                </li>
                <li>
                    <a href="?page=<?php echo $totalPages; ?>" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Last</a>
                </li>
            </ul>
        </nav>
    </div>

</body>

</html>