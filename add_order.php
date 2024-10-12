<?php
$page_title = 'Add Order';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$all_orders = find_all('orders');
$order_id = last_id('orders');
$new_order_id = $order_id['id'] + 1;
?>

<?php
if (isset($_POST['add_order'])) {
    $customer = remove_junk($db->escape($_POST['customer']));
    $paymethod = remove_junk($db->escape($_POST['paymethod']));
    $notes = remove_junk($db->escape($_POST['notes']));
    $current_date = make_date();

    if (empty($errors)) {
        $sql  = "INSERT INTO orders (id, customer, paymethod, notes, date)";
        $sql .= " VALUES ('{$new_order_id}', '{$customer}', '{$paymethod}', '{$notes}', '{$current_date}')";

        if ($db->query($sql)) {
            $session->msg("s", "Successfully Added Order");
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
            background-color: #eaf5e9; /* Light green color */
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
                        <div class="mb-4">
                            <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="customer" placeholder="Customer" required>
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
