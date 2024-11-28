<?php
$page_title = 'Add Product';
require_once('includes/load.php');
page_require_level(2);

$all_categories = find_all('categories');
$all_photo = find_all('media');

if (isset($_POST['add_product'])) {
    $req_fields = array('product-title', 'product-category', 'product-quantity', 'cost-price', 'sale-price');
    validate_fields($req_fields);
    if (empty($errors)) {
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = remove_junk($db->escape($_POST['product-category']));
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['cost-price']));
        $p_sale  = remove_junk($db->escape($_POST['sale-price']));
        $media_id = (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") ? '0' : remove_junk($db->escape($_POST['product-photo']));

        $date    = make_date();

        // Check if the product already exists by name
        $query_check = "SELECT * FROM products WHERE name = '{$p_name}' LIMIT 1";
        $result_check = $db->query($query_check);
        
        if ($result_check && $db->num_rows($result_check) > 0) {
            $session->msg('d', 'Product with this name already exists!');
            redirect('add_product.php', false);
        } else {
            // Insert new product
            $query  = "INSERT INTO products (name, quantity, buy_price, sale_price, category_id, media_id, date) 
                       VALUES ('{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}')";

            if ($db->query($query)) {
                $product = last_id("products");
                $product_id = $product['id'];
                if ($product_id == 0) {
                    $session->msg('d', ' Sorry failed to add!');
                    redirect('add_product.php', false);
                }

                $quantity = $p_qty;
                $comments = "initial stock";
                $sql  = "INSERT INTO stock (product_id, quantity, comments, date) 
                         VALUES ('{$product_id}', '{$quantity}', '{$comments}', '{$date}')";
                $result = $db->query($sql);
                if ($result && $db->affected_rows() === 1) {
                    $session->msg('s', "Product added successfully.");
                    redirect('products.php', false);
                }
            } else {
                $session->msg('d', ' Sorry failed to add!');
                redirect('add_product.php', false);
            }
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
    }
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
        /* Custom Header Background */
        .custom-header {
            background-color: #d1fae5; /* bg-green-50 */
        }

        /* Card Styling */
        .card {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #45a049;
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

        /* Table Styling */
        table th, table td {
            padding: 0.75rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        table th {
            background-color: #f9fafb;
            font-weight: bold;
        }

        table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Select Box Styling */
        select.form-control {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            width: 100%;
            background-color: #f9fafb;
            font-size: 1rem;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Include header -->
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-left mt-10">
        <div class="w-full sm:w-2/3 lg:w-1/3">
            <div class="card">
                <!-- Custom Header Section -->
                <div class="custom-header p-6 border-b">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-2" style="font-size: 20px;"></i>
                        <strong class="text-3xl font-bold">Add New Product</strong>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <?php echo display_msg($msg); ?>

                    <form method="post" action="add_product.php" class="clearfix">
                        <!-- Product Name -->
                        <div class="mb-4">
                            <input type="text" class="form-input" name="product-title" placeholder="Product Name" required>
                        </div>

                        <!-- Product Category -->
                        <div class="mb-4">
                            <select class="form-input" name="product-category" required>
                                <?php foreach ($all_categories as $cat): ?>
                                    <option value="<?php echo (int)$cat['id']; ?>"><?php echo remove_junk($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Product Quantity -->
                        <div class="mb-4">
                            <input type="number" class="form-input" name="product-quantity" placeholder="Quantity" required>
                        </div>

                        <!-- Cost Price -->
                        <div class="mb-4">
                            <input type="number" step="0.01" class="form-input" name="cost-price" placeholder="Cost Price" required>
                        </div>

                        <!-- Sale Price -->
                        <div class="mb-4">
                            <input type="number" step="0.01" class="form-input" name="sale-price" placeholder="Sale Price" required>
                        </div>

                        <!-- Product Photo -->
                        <div class="mb-4">
                            <select class="form-input" name="product-photo">
                                <option value="0">Select Photo</option>
                                <?php foreach ($all_photo as $photo): ?>
                                    <option value="<?php echo (int)$photo['id']; ?>"><?php echo remove_junk($photo['file_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button type="submit" name="add_product" class="btn-primary">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
