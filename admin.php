<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(2);

  // Run the Flask API to fetch the sales forecast
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/predict_sales"); // Ensure this URL is correct
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $forecast = curl_exec($ch);
  curl_close($ch);
  
  var_dump($forecast);  // Check the response from the Flask API
  
  // Decode the JSON output from the Flask API into a PHP array
  $sales_forecast = json_decode($forecast, true);
  
  

// Ensure that the $sales_forecast is not empty and contains the expected data
if (empty($sales_forecast)) {
  $sales_forecast = [];
}

$months = [];
$predicted_sales = [];

// Assuming the response from Flask API contains 'date' and 'predicted_sales'
foreach ($sales_forecast as $data) {
  $months[] = date('F Y', strtotime($data['date']));  // Convert 'date' to human-readable format
  $predicted_sales[] = (float) $data['predicted_sales'];  // Convert predicted sales to float for numerical processing
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

  <!-- Custom CSS -->
  <style>
    .custom-class { color: #eaf5e9; }
    th, td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
    th { background-color: #eaf5e9; /* Light green color */ }
    .text-right { text-align: right; }
    .nowrap { white-space: nowrap; }
    .table a {
      color: #000;
    }
  </style>

  <!-- Include Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container { width: 100%; height: 400px; }
  </style>
</head>
<body class="bg-gray-100">

<?php
  $c_categorie = count_by_id('categories');
  $c_product = count_by_id('products');
  $c_sale = count_by_id('sales');
  $c_user = count_by_id('users');
  $products_sold = find_higest_saleing_product('6');
  $recent_products = find_recent_product_added('6');
  $recent_sales = find_recent_sale_added('6');
  $user = current_user();
?>

<?php include_once('layouts/header.php'); ?>

<!-- Display messages -->
<div class="flex justify-center">
  <div class="w-11/12 md:w-3/4">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<!-- Dashboard panels -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <?php if($user['user_level'] == 1): ?>
    <div class="bg-green-500 p-4 rounded-lg shadow-md text-white flex items-center">
        <i class="glyphicon glyphicon-user text-7xl"></i>
        <div class="ml-8 flex flex-col justify-center h-full text-center">
            <h2 class="text-4xl font-bold ml-9"><?php echo $c_user['total']; ?></h2>
            <p class="text-3xl font-bold ml-10">Users</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-red-500 p-4 rounded-lg shadow-md text-white flex items-center">
        <i class="glyphicon glyphicon-list text-7xl"></i>
        <div class="ml-8 flex flex-col justify-center h-full text-center">
            <h2 class="text-4xl font-bold ml-6"><?php echo $c_categorie['total']; ?></h2>
            <p class="text-3xl font-bold ml-6">Categories</p>
        </div>
    </div>

    <div class="bg-blue-500 p-4 rounded-lg shadow-md text-white flex items-center">
        <i class="glyphicon glyphicon-shopping-cart text-7xl"></i>
        <div class="ml-8 flex flex-col justify-center h-full text-center">
            <h2 class="text-4xl font-bold ml-6"><?php echo $c_product['total']; ?></h2>
            <p class="text-3xl font-bold ml-6">Products</p>
        </div>
    </div>

    <div class="bg-yellow-500 p-4 rounded-lg shadow-md text-white flex items-center">
        <span class="text-7xl font-bold">₱</span>
        <div class="ml-8 flex flex-col justify-center h-full text-center">
            <h2 class="text-4xl font-bold ml-8"><?php echo $c_sale['total']; ?></h2>
            <p class="text-3xl font-bold ml-8">Total Sales</p>
        </div>
    </div>
</div>

<!-- Welcome Panel -->
<script>
  function closePanel() {
    var x = document.getElementById("myDIV");
    x.style.display = (x.style.display === "none") ? "block" : "none";
  }
</script>

<div class="mt-4" id="myDIV">
  <div class="bg-white p-3 rounded-lg shadow-md">
    <div class="flex justify-end">
      <button onclick="closePanel();" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </div>
    <div class="jumbotron text-center">
      <h3 class="text-2xl font-semibold">Welcome!</h3>
      <p>Contact support for assistance.</p>
    </div>
  </div>
</div>

<!-- Highest Selling Products, Latest Sales, Recently Added Products -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
  <div class="bg-white p-3 rounded-lg shadow-md">
    <div class="font-bold text-lg mb-2">
      <i class="glyphicon glyphicon-th"></i> HIGHEST SELLING PRODUCTS
    </div>
    <table class="table w-full">
      <thead>
        <tr>
          <th>Product</th>
          <th>Total Sold</th>
          <th>Total Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products_sold as $product_sold): ?>
        <tr>
          <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
          <td><?php echo (int)$product_sold['totalSold']; ?></td>
          <td><?php echo (int)$product_sold['totalQty']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-md">
    <div class="font-bold text-lg mb-2">
      <i class="glyphicon glyphicon-th"></i> LATEST SALES
    </div>
    <table class="table w-full">
      <thead>
        <tr>
          <th>#</th>
          <th>Product</th>
          <th class="text-center nowrap">Date</th>
          <th class="text-right">Total Sale</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_sales as $recent_sale): ?>
        <tr>
          <td><?php echo count_id(); ?></td>
          <td>
            <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>">
              <?php echo remove_junk(first_character($recent_sale['name'])); ?>
            </a>
          </td>
          <td class="text-right nowrap"><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
          <td class="text-right">₱<?php echo number_format((float)$recent_sale['price'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-md">
    <div class="font-bold text-lg mb-2">
      <i class="glyphicon glyphicon-th"></i> RECENTLY ADDED PRODUCTS
    </div>
    <table class="table w-full">
      <thead>
        <tr>
          <th>Product</th>
          <th>Category</th>
          <th class="text-right">Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_products as $recent_product): ?>
        <tr>
          <td>
            <a href="view_product.php?id=<?php echo (int)$recent_product['id']; ?>">
              <?php echo remove_junk(first_character($recent_product['name'])); ?>
            </a>
          </td>
          <td><?php echo remove_junk(first_character($recent_product['category'])); ?></td>
          <td class="text-right">₱<?php echo number_format((float)$recent_product['sale_price'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>



<!-- Sales Forecast Chart -->
<div class="bg-white p-3 rounded-lg shadow-md mt-4">
  <div class="chart-container">
    <canvas id="salesForecastChart"></canvas>
  </div>
</div>

<!-- JavaScript to render the chart -->
<script>
// Sales Forecast Chart
const months = <?php echo json_encode($months); ?>;
const predicted_sales = <?php echo json_encode($predicted_sales); ?>;

// Log to check if data is correctly populated
console.log("Months: ", months);
console.log("Predicted Sales: ", predicted_sales);

// Create a chart
const ctx = document.getElementById('salesForecastChart').getContext('2d');
const salesForecastChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Predicted Quantity Sold',
            data: predicted_sales,
            borderColor: 'rgba(75, 192, 192, 1)', // Line color
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill color
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Quantity Sold'
                }
            }
        }
    }
});




</script>
</body>
</html>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
