<?php
$page_title = 'Add Stock';
require_once('includes/load.php');
// Check user permission level
page_require_level(2);

// Get all products from the database
$all_products = find_all('products');

// Sort products alphabetically by name (this includes numbers and letters)
usort($all_products, function($a, $b) {
    return strcasecmp($a['name'], $b['name']);
});

// Get the product ID from the URL, if present
$product_id_from_url = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['add_stock'])) {
    $req_field = array('product_id', 'quantity');
    validate_fields($req_field);
    $product_id = remove_junk($db->escape($_POST['product_id']));
    $quantity = remove_junk($db->escape($_POST['quantity']));
    $comments = remove_junk($db->escape($_POST['comments']));
    
    // Check if comments are empty, and set to "No Comment" if true
    if (empty($comments)) {
        $comments = "No Comment";
    }

    $current_date = make_date();

    if (empty($errors)) {
        $sql  = "INSERT INTO stock (product_id, quantity, comments, date)";
        $sql .= " VALUES ('{$product_id}', '{$quantity}', '{$comments}', '{$current_date}')";
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            increase_product_qty($quantity, $product_id);
            $session->msg("s", "Successfully Added");
            redirect('stock.php', false);
        } else {
            $session->msg("d", "Sorry, failed to insert.");
            redirect('add_stock.php?id=' . $product_id_from_url, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_stock.php?id=' . $product_id_from_url, false);
    }
}


include_once('layouts/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .custom-header {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="mt-6 ml-6">
    <div class="w-2/6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="custom-header p-4">
                <div class="flex items-center">
                    <span class="glyphicon glyphicon-th" style="font-size: 20px;"></span>
                    <h2 class="text-3xl font-bold ml-2">Add Stock</h2>
                </div>
            </div>
            <div class="p-4">
                <?php echo display_msg($msg); ?>
                <form method="post" action="">
                <div class="mb-4">
                    <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Select Product</label>
                    <select class="form-control border rounded w-full py-2 px-3" name="product_id" id="product_id" required>
                        <?php foreach ($all_products as $product): ?>
                            <option value="<?php echo $product['id']; ?>" <?php echo ($product['id'] == $product_id_from_url) ? 'selected' : ''; ?>>
                                <?php echo $product['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Product Quantity</label>
                        <input type="number" class="form-control border rounded w-full py-2 px-3" name="quantity" placeholder="Product Quantity" required>
                    </div>

                    <div class="mb-4">
                        <label for="comments" class="block text-gray-700 text-sm font-bold mb-2">Comments</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="comments" placeholder="Comments">
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="add_stock" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Add to Inventory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
