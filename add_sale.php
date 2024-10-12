<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

// Get the last order ID
$order_id = last_id('orders');

// Fix: Properly access the 'id' value from $order_id array
if(isset($order_id['id'])){
    $o_id = $order_id['id'];
} else {
    // Handle case where no order ID is found
    $session->msg('d','Order ID not found.');
    redirect('add_sale.php', false);
}

// Fetch sales related to the current order
$sales = find_sales_by_order_id($o_id); // Custom function to find sales by order ID

if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total', 'date' );
    validate_fields($req_fields);

    if(empty($errors)){
        // Properly escape and cast all inputs to prevent SQL injection or invalid types
        $p_id   = $db->escape((int)$_POST['s_id']);
        $s_qty  = $db->escape((int)$_POST['quantity']);
        $s_total = $db->escape($_POST['total']);
        $date   = $db->escape($_POST['date']);
        $s_date = make_date();

        // Find the product details
        $product = find_by_id("products", $p_id);
        
        // Check if the product exists and has sufficient quantity
        if(!$product){
            $session->msg('d', 'Product not found.');
            redirect('add_sale.php', false);
        }
        if((int)$product['quantity'] < $s_qty) {
            $session->msg('d','Insufficient Quantity for Sale!');
            redirect('add_sale.php', false);
        }

        // Insert the sale record
        $sql  = "INSERT INTO sales (product_id, order_id, qty, price, date)";
        $sql .= " VALUES ('{$p_id}', '{$o_id}', '{$s_qty}', '{$s_total}', '{$s_date}')";

        if($db->query($sql)){
            // Decrease the product quantity after a successful sale
            decrease_product_qty($s_qty, $p_id);
            $session->msg('s',"Sale added successfully.");
            redirect('add_sale.php', false);
        } else {
            $session->msg('d','Failed to add sale.');
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
      <div class="form-group">
        <div class="input-group">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">Find It</button>
          </span>
          <input type="text" id="sug_input" class="form-control" name="title" placeholder="Search for product name">
        </div>
        <div id="result" class="list-group"></div>
      </div>
    </form>
  </div>

  <div class="col-md-6">
    <div class="panel">
      <div class="jumbotron text-center">
        <h3>Order #<?php echo $o_id; ?></h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
          <table class="table table-bordered">
            <thead>
              <th>Item</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Date</th>
              <th>Action</th>
            </thead>
            <tbody>
              <?php if(!empty($sales)): ?>
                <?php foreach($sales as $sale): ?>
                  <tr>
                    <td><?php echo remove_junk($sale['name']); ?></td>
                    <td><?php echo remove_junk($sale['price']); ?></td>
                    <td><?php echo (int)$sale['qty']; ?></td>
                    <td><?php echo (int)$sale['qty'] * (float)$sale['price']; ?></td>
                    <td><?php echo read_date($sale['date']); ?></td>
                    <td>
                      <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-xs btn-warning" title="Edit">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="delete_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-xs btn-danger" title="Remove">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6">No Sales Found for This Order</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sugInput = document.getElementById("sug_input");
        const resultDiv = document.getElementById("result");

        sugInput.addEventListener("keyup", function() {
            const query = sugInput.value;
            if (query.length > 0) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        resultDiv.innerHTML = this.responseText;
                    }
                };
                xhr.send("title=" + encodeURIComponent(query));
            } else {
                resultDiv.innerHTML = ""; // Clear the result if the input is empty
            }
        });
    });
</script>

<?php include_once('layouts/footer.php'); ?>
