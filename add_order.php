<?php
$page_title = 'Add Order';
require_once('includes/load.php');
page_require_level(2);

// Check if it's a new day and reset counts if needed
$current_date = date('Y-m-d'); // Get the current date in 'Y-m-d' format

// Fetch the last order date from the database
$last_order_date_query = $db->query("SELECT MAX(date) AS last_order_date FROM orders");
$last_order_date_result = $db->fetch_assoc($last_order_date_query);
$last_order_date = $last_order_date_result['last_order_date'] ?? '0000-00-00';

if ($last_order_date != $current_date) {
    // It's a new day, so reset the order and customer count
    $new_customer_num = 1;
    $new_order_id = 1;
} else {
    // It's the same day, so increment based on the last record
    $last_customer_query = $db->query("SELECT MAX(CAST(SUBSTRING(customer, 10) AS UNSIGNED)) AS last_customer_num FROM orders");
    $last_customer_num = $db->fetch_assoc($last_customer_query)['last_customer_num'] ?? 0;
    $new_customer_num = $last_customer_num + 1;

    $last_order_id = last_id('orders')['id'];
    $new_order_id = $last_order_id + 1;
}

$new_customer_name = 'Customer ' . $new_customer_num;

if (isset($_POST['add_order'])) {
    $customer = $new_customer_name; // Automatically set customer name
    $paymethod = remove_junk($db->escape($_POST['paymethod']));
    $notes = remove_junk($db->escape($_POST['notes']));

    if (empty($errors)) {
        $sql  = "INSERT INTO orders (id, customer, paymethod, notes, date)";
        $sql .= " VALUES ('{$new_order_id}', '{$customer}', '{$paymethod}', '{$notes}', '{$current_date}')";

        if ($db->query($sql)) {
            $session->msg("s", "Successfully Added Order as {$customer}");
            redirect('add_sale_to_order.php?id=' . $new_order_id, false);
        } else {
            $session->msg("d", "Sorry, Failed to Insert.");
            redirect('add_order.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_order.php', false);
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
        .custom-header {
            background-color: #eaf5e9;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-left mt-10">
        <div class="w-full sm:w-2/3 lg:w-1/3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="custom-header p-6 border-b">
                    <div class="flex items-center">
                        <i class="fas fa-list-alt mr-2" style="font-size: 20px;"></i>
                        <strong class="text-3xl font-bold">Add Order</strong>
                    </div>
                </div>
                <div class="p-6">
                    <?php echo display_msg($msg); ?>
                    <div class="text-center mb-4">
                        <h3 class="text-3xl font-semibold">#<?php echo $new_order_id; ?></h3>
                    </div>
                    <form method="post" action="add_order.php" class="clearfix">
                        <!-- Display customer name -->
                        <div class="mb-4">
                            <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full bg-gray-200" name="customer" value="<?php echo $new_customer_name; ?>" readonly>
                        </div>

                        <div class="mb-4">
                            <select class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="paymethod" required>
                                <option value="">Select Payment Method</option>
                                <option value="Cash">Cash</option>
                                <option value="Gcash">Gcash</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="notes" placeholder="Notes">
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" name="add_order" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Start Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
