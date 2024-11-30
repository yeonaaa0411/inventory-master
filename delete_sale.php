<?php
  require_once('includes/load.php');
  // Check user level permission
  page_require_level(2);
?>

<?php
  // Get sale record by ID
  $d_sale = find_by_id('sales', (int)$_GET['id']);

  if (!$d_sale) {
    $session->msg("d", "Missing sale id.");
    redirect('sales.php');
  }

  // Increase product stock back
  if (increase_product_qty($d_sale['qty'], $d_sale['product_id'])) {
    // Delete the sale record
    $delete_id = delete_by_id('sales', (int)$d_sale['id']);
  }

  // If sale is deleted successfully
  if ($delete_id) {
    $session->msg("s", "Sale has been successfully removed from the records.");
    redirect('sales.php');
  } else {
    $session->msg("d", "Sale deletion failed.");
    redirect('sales.php');
  }
?>
