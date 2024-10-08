<?php
  $page_title = 'All orders';
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(2);
  
  $all_orders = find_all('orders');
  $order_id = last_id('orders');
  $new_order_id = $order_id['id'] + 1;
?>

<?php
 if(isset($_POST['add_order'])){
   $customer = remove_junk($db->escape($_POST['customer']));
   $paymethod = remove_junk($db->escape($_POST['paymethod']));
   $notes = remove_junk($db->escape($_POST['notes']));
   $current_date = make_date();

   if(empty($errors)){
      $sql  = "INSERT INTO orders (id,customer,paymethod,notes,date)";
      $sql .= " VALUES ('{$new_order_id}','{$customer}','{$paymethod}','{$notes}','{$current_date}')";
      
      if($db->query($sql)){
        $session->msg("s", "Successfully Added order");
        redirect('add_sale_to_order.php?id=' . $new_order_id, false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('add_order.php', false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_order.php', false);
   }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-list-alt"></span>
            <span>Add Order</span>
          </strong>
        </div>
        <div class="panel-body">
          <div class="text-center">
            <h3>#<?php echo $new_order_id; ?></h3>
          </div>

          <form method="post" action="" class="clearfix">
            <div class="form-group">
              <label for="customer" class="control-label">Customer Name</label>
              <input type="text" class="form-control" name="customer" placeholder="Customer" required>
            </div>

            <div class="form-group">
              <label for="paymethod" class="control-label">Payment Method</label>
              <select class="form-control" name="paymethod" required>
                <option value="">Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="Gcash">Gcash</option>
              </select>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" name="notes" placeholder="Notes">
            </div>

            <div class="form-group clearfix">
              <div class="pull-right">
                <button type="submit" name="add_order" class="btn btn-info">Start Order</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
