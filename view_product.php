<?php
$page_title = 'All Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);

// Fetch product information by ID
$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');

if (!$product) {
    $session->msg("d", "Missing product id.");
}

// Fetch sales data for the last 5 months for the specific product
$product_id = (int)$_GET['id'];
$current_date = date('Y-m-d');
$start_date = date('Y-m-d', strtotime('-5 months', strtotime($current_date)));

$sales_data = find_product_sales_by_month($product_id, $start_date, $current_date);

include_once('layouts/header.php');
?>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom Styling -->
<style>
    .header-bg {
        background-color: #eaf5e9 !important;
    
    }
    .thead {
        background-color: #eaf5e9 !important;
    
    }
</style>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row header-bg">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix header-bg">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Product Detail</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-6">
                        <h4><?php echo remove_junk(first_character($product['name'])); ?></h4>
                        <div class="text-center"><label>Sales Data by Month:</label></div>

                        <!-- Canvas for Chart.js -->
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>

                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        <?php
                        foreach ($all_photo as $photo) {
                            if ($product['media_id'] == $photo['id']) {
                        ?>
                                <img class="img-thumbnail" src="uploads/products/<?php echo $photo['file_name']; ?>" alt="Product Image">
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="panel-body">
                            <table class="table table-bordered">
                            <thead class="thead">
                                <tr>
                                    <th class="text-center" style="width: 10%;">Category</th>
                                    <th class="text-center" style="width: 10%;">Stock</th>
                                    <th class="text-center" style="width: 15%;">Cost Price</th>
                                    <th class="text-center" style="width: 15%;">Sale Price</th>
                                    <th class="text-center" style="width: 15%;">Product Added</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                            </thead>

                                <tbody>
                                    <tr>
                                        <?php
                                        foreach ($all_categories as $category) {
                                            if ($product['category_id'] == $category['id']) {
                                                break;
                                            }
                                        }
                                        ?>
                                        <td class="text-center"><?php echo remove_junk($category['name']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                        <td class="text-center"><?php echo read_date($product['date']); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="add_stock.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Add">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Prepare sales data
    const salesLabels = <?php echo json_encode(array_keys($sales_data)); ?>; // Month names
    const salesValues = <?php echo json_encode(array_values($sales_data)); ?>; // Sales counts

    const salesData = {
        labels: salesLabels,
        datasets: [{
            label: 'Sales',
            data: salesValues,
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    const config = {
        type: 'bar',
        data: salesData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    // Render the chart
    const salesChart = new Chart(
        document.getElementById('salesChart'),
        config
    );
</script>

<?php include_once('layouts/footer.php'); ?>
