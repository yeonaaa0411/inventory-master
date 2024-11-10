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
    redirect('stock.php');
  }
?>

<?php
if(isset($_POST['edit_stock'])){
  $req_field = array('product_id', 'quantity');
  validate_fields($req_field);
  $product_id = remove_junk($db->escape($_POST['product_id']));
  $quantity = remove_junk($db->escape($_POST['quantity']));

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
      redirect('stock.php', false);
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
      background-color: #eaf5e9;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    tr:hover {
      background-color: #f7fafc;
    }
    .custom-header {
      background-color: #eaf5e9; /* Light green color */
    }
  </style>
</head>
<body class="bg-gray-100">

  <!-- Include header -->
  <?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>

  <div class="flex justify-start mt-10">
    <div class="w-full sm:w-3/5 lg:w-2/5">
      <div class="bg-white shadow-md rounded-lg">
        <div class="custom-header p-10 border-b">
          <div class="flex items-center">
            <i class="fas fa-box mr-2" style="font-size: 20px;"></i>
            <strong class="text-3xl font-bold">EDIT STOCK</strong>
          </div>
        </div>
        <div class="p-10"> <!-- Increased padding for more vertical space -->
          <form method="post" action="" class="clearfix">
            <div class="mb-8"> <!-- Increased bottom margin -->
              <label for="name" class="text-lg font-semibold"><?php echo $product['name']; ?></label>
              <input type="hidden" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="product_id" value="<?php echo $stock['product_id']; ?>">
            </div>

            <div class="mb-8"> <!-- Increased bottom margin -->
              <div class="input-group flex space-x-4">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-shopping-cart"></i>
                </span>
                <input type="number" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="quantity" value="<?php echo $stock['quantity']; ?>" placeholder="Product Quantity">
              </div>
            </div>

            <div class="mb-8">
              <input type="text" class="form-control border border-gray-300 rounded-md px-4 py-4 w-full" name="comments" value="<?php echo remove_junk(ucfirst($stock['comments'])); ?>" placeholder="Notes">
            </div>

            <div class="text-right">
              <button type="submit" name="edit_stock" class="bg-blue-500 text-white px-6 py-4 rounded hover:bg-blue-600">Update Inventory</button>
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
