<?php
$page_title = 'All orders';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

$all_orders = find_all('orders');
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>All Orders</span>
                </strong>
                <div class="pull-right">
                    <a href="add_order.php" class="btn btn-primary">Add Order</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th class="text-center" style="width: 50px;">Customer</th>
                            <th class="text-center" style="width: 50px;">Pay Method</th>
                            <th class="text-center" style="width: 50px;">Notes</th>
                            <th class="text-center" style="width: 50px;">Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_orders as $order): ?>
                        <tr>
                            <td class="text-center">
                                <a href="sales_by_order.php?id=<?php echo (int)$order['id']; ?>">
                                    <?php echo (int)$order['id']; ?>
                                </a>
                            </td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['customer'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['paymethod'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['notes'])); ?></td>
                            <td class="text-center"><?php echo remove_junk(ucfirst($order['date'])); ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_order.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <a href="delete_order.php?id=<?php echo (int)$order['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Remove">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
