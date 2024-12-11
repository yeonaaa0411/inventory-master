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
            redirect('orders.php', false); // Redirect to orders.php after successful update
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
    <!-- Custom CSS -->
    <style>
        th, td {
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        th {
            background-color: rgba(236, 253, 245, 1); /* Light green color */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        tr:hover {
            background-color: #f7fafc;
        }
        .custom-header {
            background-color: rgba(236, 253, 245, 1); /* Light green color */
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

        .header-bg {
            background-color: #d1fae5; /* Light green color */
        }

        /* Dropdown and Select Styling */
        select.form-input {
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
        }

    </style>
</head>
<body class="bg-gray-100">

<!-- Include header -->
<?php include_once('layouts/header.php'); ?>

<div class="flex justify-left mt-6">
    <div class="w-full md:w-1/2 lg:w-1/3">
        <?php echo display_msg($msg); ?>
        <div class="bg-white shadow-md rounded-lg">
            <div class="custom-header p-10 border-b">
                <div class="flex items-center">
                    <i class="fas fa-edit mr-2" style="font-size: 20px;"></i>
                    <strong class="text-3xl font-bold">Edit Order</strong>
                </div>
            </div>
            <div class="p-10"> <!-- Increased padding for more vertical space -->
                <div class="text-center mb-4">
                    <h3 class="text-3xl font-semibold">#<?php echo remove_junk($order['id']); ?></h3>
                </div>
                <form method="post" action="edit_order.php?id=<?php echo (int)$order['id']; ?>">
                <div class="mb-8">
    <label for="customer" class="form-label">Customer</label>
    <input type="text" class="form-input" name="customer" value="<?php echo remove_junk(ucfirst($order['customer'])); ?>" readonly>
</div>

<div class="mb-8">
    <label for="paymethod" class="form-label">Payment Method</label>
    <input type="text" class="form-input" name="paymethod" value="<?php echo remove_junk(ucfirst($order['paymethod'])); ?>" readonly>
</div>

<div class="mb-8">
    <label for="notes" class="form-label">Notes</label>
    <textarea class="form-input" name="notes"><?php echo remove_junk($order['notes']); ?></textarea>
</div>

<div class="mb-8">
    <label for="date" class="form-label">Order Date</label>
    <input type="text" class="form-input" name="date" value="<?php echo remove_junk($order['date']); ?>" readonly>
</div>


                    <div class="flex justify-center">
                        <button type="submit" name="edit_order" class="btn-primary">Update Order</button>
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
