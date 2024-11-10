<?php
$page_title = 'Edit Order';
require_once('includes/load.php');
// Check user permissions
page_require_level(2);

// Display the order
$order = find_by_id('orders', (int)$_GET['id']);
if (!$order) {
    $session->msg("d", "Missing order id.");
    redirect('orders.php');
}

if (isset($_POST['edit_order'])) {
    $customer = remove_junk($db->escape($_POST['customer']));
    $paymethod = remove_junk($db->escape($_POST['paymethod']));
    $notes = remove_junk($db->escape($_POST['notes']));
    $date = remove_junk($db->escape($_POST['date']));
    if ($date == 0) {
        $date = make_date();
    }

    if (empty($errors)) {
        $sql = "UPDATE orders SET";
        $sql .= " customer='{$customer}', paymethod='{$paymethod}', notes='{$notes}', date='{$date}'";
        $sql .= " WHERE id='{$order['id']}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg("s", "Successfully updated order");
            redirect('sales_by_order.php?id=' . (int)$order['id'], false); // Redirect to the edited order's page
        } else {
            $session->msg("d", "No changes made to the order");
            redirect('edit_order.php?id=' . (int)$order['id'], false); // Stay on the editing page if update failed
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_order.php?id=' . (int)$order['id'], false); // Stay on the editing page if there are errors
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
        .header-bg {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
</head>
<body class="bg-gray-100">

<?php include_once('layouts/header.php'); ?>

<div class="flex justify-left mt-6">
    <div class="w-full md:w-1/2 lg:w-1/3">
        <?php echo display_msg($msg); ?>
        <div class="bg-white shadow-md rounded-lg">
            <div class="header-bg p-6 border-b rounded-t-lg">
                <div class="flex items-center">
                    <i class="fas fa-edit mr-2" style="font-size: 20px;"></i>
                    <strong class="text-3xl font-bold">Edit Order</strong>
                </div>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <h3 class="text-3xl font-semibold">#<?php echo remove_junk($order['id']); ?></h3>
                </div>
                <form method="post" action="edit_order.php?id=<?php echo (int)$order['id']; ?>">
                    <div class="mb-4">
                        <label for="customer" class="block text-gray-700 text-sm font-bold mb-2">Customer</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="customer" value="<?php echo remove_junk(ucfirst($order['customer'])); ?>">
                    </div>

                    <div class="mb-4">
                        <label for="paymethod" class="block text-gray-700 text-sm font-bold mb-2">Payment Method</label>
                        <select class="form-control border rounded w-full py-2 px-3" name="paymethod">
                            <option value="Cash" <?php if ($order['paymethod'] === "Cash") echo "selected"; ?>>Cash</option>
                            <option value="Gcash" <?php if ($order['paymethod'] === "Gcash") echo "selected"; ?>>Gcash</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="notes" value="<?php echo remove_junk(ucfirst($order['notes'])); ?>" placeholder="Notes">
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                        <input type="date" class="form-control border rounded w-full py-2 px-3" name="date" value="<?php echo remove_junk($order['date']); ?>">
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" name="edit_order" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Update Order
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
