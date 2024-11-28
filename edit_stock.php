<?php
  $page_title = 'Edit Stock';
  require_once('includes/load.php');
  page_require_level(2);
?>

<?php
  $stock = find_by_id('stock', (int)$_GET['id']);
  $product = find_by_id('products', (int)$stock['product_id']);

  if(!$stock){
    $session->msg("d", "Missing order id.");
    redirect('inventory.php');
  }
?>

<?php
if(isset($_POST['edit_stock'])){
  $req_field = array('product_id', 'quantity');
  validate_fields($req_field);
  $product_id = remove_junk($db->escape($_POST['product_id']));
  $quantity = remove_junk($db->escape($_POST['quantity']));

  // Check if the quantity or comments have changed
  if ($product_id == $stock['product_id'] && $quantity == $stock['quantity'] && $_POST['comments'] == $stock['comments']) {
    $session->msg("w", "No changes were made.");
    redirect('edit_stock.php?id=' . $stock['id'], false); // Redirect back to the same page
    exit(); // Prevent further code execution
  }

  $s_qty_diff = 0;
  if ($quantity != $stock['quantity']) {
    if ($quantity > $stock['quantity']) {
      $s_qty_diff = $quantity - $stock['quantity'];
      $decrease_quantity_flag = false;
    } else {
      $s_qty_diff = $stock['quantity'] - $quantity;
      $decrease_quantity_flag = true;
    }
  }

  $comments = remove_junk($db->escape($_POST['comments']));
  $current_date = make_date();

  if (empty($errors)) {
    $sql = "UPDATE stock SET";
    $sql .= " product_id='{$product_id}', quantity='{$quantity}', comments='{$comments}', date='{$current_date}'";
    $sql .= " WHERE id='{$stock['id']}'";

    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      if ($s_qty_diff > 0) {
        if ($decrease_quantity_flag) {
          decrease_product_qty($s_qty_diff, $product_id);
        } else {
          increase_product_qty($s_qty_diff, $product_id);
        }
      }
      $session->msg("s", "Successfully updated");
      redirect('inventory.php', false);
    } else {
      $session->msg("d", "Sorry! Failed");
      redirect('edit_stock.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_stock.php', false);
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
      background-color: rgba(236, 253, 245, 1); /* Apply the bg-green-50 color from your edit_product.php */
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
  <?php echo display_msg($msg); ?>

  <div class="flex justify-start mt-10">
    <div class="w-full sm:w-3/5 lg:w-3/5">
      <div class="bg-white shadow-md rounded-lg">
        <div class="custom-header p-10 border-b">
          <div class="flex items-center">
            <i class="fas fa-cogs mr-2" style="font-size: 20px;"></i>
            <strong class="text-3xl font-bold">Edit Stock</strong>
          </div>
        </div>
        <div class="p-10"> <!-- Increased padding for more vertical space -->
          <form method="post" action="edit_stock.php?id=<?php echo (int)$stock['id'] ?>" class="clearfix">
            <div class="mb-8"> <!-- Increased bottom margin -->
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-input" name="product-title" value="<?php echo remove_junk($product['name']); ?>" placeholder="Product Name" readonly>
              </div>
            </div>

            <div class="mb-8 flex space-x-4"> <!-- Increased bottom margin -->
              <!-- Product Quantity -->
              <input type="number" class="form-input" name="quantity" value="<?php echo remove_junk($stock['quantity']); ?>" placeholder="Quantity" required>
            </div>

            <div class="mb-8">
              <label for="comments" class="form-label">Comments</label>
              <textarea class="form-input" name="comments" placeholder="Comments" rows="4"><?php echo remove_junk($stock['comments']); ?></textarea>
            </div>

            <div class="flex justify-center">
              <button type="submit" name="edit_stock" class="btn-primary">Update Stock</button>
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
