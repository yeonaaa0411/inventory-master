<?php
$page_title = 'All Product';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$all_categories = find_all('categories');

// Initialize products as an empty array
$products = [];

// Check if the form is submitted and a category is selected
if (isset($_POST['update_category']) && !empty($_POST['product-category'])) {
    $products = find_products_by_category((int)$_POST['product-category']);
} else {
    $products = join_product_table();
}

// Sort the products alphabetically by name
usort($products, function($a, $b) {
    return strcmp($a['name'], $b['name']); // Compare product names
});
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
            border: 1px solid #e2e8f0; /* Change border to 1px for better visibility */
        }

        th {
            background-color: #eaf5e9;
        }

        table {
            border-collapse: collapse; /* Ensure borders collapse */
            width: 100%; /* Ensure table takes full width */
        }

        tr:hover {
            background-color: #f7fafc; /* Light hover effect for rows */
        }

        .header-bg {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
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
                    <i class="fas fa-box mr-2"></i> <!-- Icon for products -->
                    <?php
                    if (isset($_POST['update_category'])) {
                        echo "Products by Category";
                    } else {
                        echo "All Products";
                    }
                    ?>
                </strong>
            </div>
            <div class="p-4">
                <form method="post" action="">
                    <div class="flex space-x-4">
                    <select class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="product-category">
                        <?php foreach ($all_categories as $cat): ?>
                            <option value="<?php echo (int)$cat['id'] ?>">
                                <?php echo remove_junk($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                        <button type="submit" name="update_category" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Filter Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-6 mx-5">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center border px-4 py-2" style="width: 50px;">#</th>
                            <th class="text-center border px-4 py-2" style="width: 10%;">Category</th>
                            <th class="text-center border px-4 py-2">Product Name</th>
                            <th class="text-center border px-4 py-2">Photo</th>
                            <th class="text-center border px-4 py-2" style="width: 10%;">Available Stock</th>
                            <th class="text-center border px-4 py-2" style="width: 10%;">Cost Price</th>
                            <th class="text-center border px-4 py-2" style="width: 10%;">Sale Price</th>
                            <th class="text-center border px-4 py-2" style="width: 10%;">Product Added</th>
                            <th class="text-center border px-4 py-2" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td class="text-center"><?php echo remove_junk($product['category']); ?></td>
                                    <td class="text-center">
                                        <a href="view_product.php?id=<?php echo (int)$product['id']; ?>">
                                            <?php echo remove_junk($product['name']); ?>
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
                                            <a href="add_stock.php?id=<?php echo (int)$product['id']; ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" title="Add">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
