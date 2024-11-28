<?php
  require_once('includes/load.php');
  // Check user level permission
  page_require_level(2);
?>

<?php
  // Get the order record by ID
  $d_order = find_by_id('orders', (int)$_GET['id']);

  if (!$d_order) {
    $session->msg("d", "Missing order id.");
    redirect('orders.php');
  }

  // Get all sales associated with this order
  $sales = find_sales_by_order_id($d_order['id']);

  // For each sale, delete it and increase the stock quantity
  if (!empty($sales)) {
    foreach ($sales as $sale) {
      // Ensure 'quantity' and 'product_id' keys exist
      if (isset($sale['quantity'], $sale['product_id'])) {
        if (delete_by_id('sales', (int)$sale['id'])) {
          increase_product_qty($sale['quantity'], $sale['product_id']);
        }
      } else {
        error_log("Missing keys in sale record: " . print_r($sale, true));
      }
    }
  }

  // Now delete the order
  $delete_id = delete_by_id('orders', (int)$d_order['id']);

  if ($delete_id) {
    $session->msg("s", "Order and related sales deleted.");
    redirect('orders.php');
  } else {
    $session->msg("d", "Order deletion failed.");
    redirect('orders.php');
  }
?>
