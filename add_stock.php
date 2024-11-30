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
            redirect('inventory.php', false);
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
            background-color: #d1fae5; /* bg-green-50 */
        }
        /* Form Input Styling */
        .form-input {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Button Styling */
    /* Button Styling */
    .btn-primary {
        background-color: #51aded; /* Set background color */
        border-color: #3d8fd8; /* Set border color */
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        border: 1px solid #3d8fd8; /* Add border */
    }

    .btn-primary:hover {
        background-color: #45a049; /* Hover effect - you can modify this if you want a different hover effect */
        border-color: #3d8fd8; /* Keep border color consistent on hover */
    }

        .card {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="mt-6 ml-6">
    <div class="w-2/6">
        <div class="card">
            <div class="custom-header p-4">
                <div class="flex items-center">
                    <h2 class="text-3xl font-bold ml-2">Add Stock</h2>
                </div>
            </div>
            <div class="p-4">
                <?php echo display_msg($msg); ?>
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="product_id" class="form-label">Select Product</label>
                        <select class="form-input" name="product_id" id="product_id" required>
                            <?php foreach ($all_products as $product): ?>
                                <option value="<?php echo $product['id']; ?>" <?php echo ($product['id'] == $product_id_from_url) ? 'selected' : ''; ?>>
                                    <?php echo $product['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="form-label">Product Quantity</label>
                        <input type="number" class="form-input" name="quantity" placeholder="Product Quantity" required>
                    </div>

                    <div class="mb-4">
                        <label for="comments" class="form-label">Comments</label>
                        <input type="text" class="form-input" name="comments" placeholder="Comments">
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="add_stock" class="btn-primary">
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
