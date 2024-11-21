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

  // Decode the JSON output from the Flask API into a PHP array
  $sales_forecast = json_decode($forecast, true);

  // Ensure that the $sales_forecast is not empty and contains the expected data
  if (empty($sales_forecast)) {
      $sales_forecast = [];
  }

  // Update to process year and quarter from Flask API
  $quarters = [];
  $predicted_sales = [];
  $predicted_sales_count = [];
  $predicted_revenue = [];
  $top_10_qty_products = [];
  $top_10_revenue_products = [];

  foreach ($sales_forecast['predictions'] as $data) {
      $quarters[] = "Q" . $data['quarter'] . " " . $data['year']; // Combine quarter and year for display
      $predicted_sales[] = (float) $data['predicted_qty']; // Convert predicted sales to float
      $predicted_sales_count[] = (float) $data['predicted_sales_count']; // Convert predicted sales count to float
      $predicted_revenue[] = (float) $data['predicted_revenue']; // Convert predicted revenue to float
  }

  // Top 10 Products
  foreach ($sales_forecast['top_10_qty_products'] as $product) {
      $top_10_qty_products[] = (float) $product['predicted_qty'];
  }

  foreach ($sales_forecast['top_10_revenue_products'] as $product) {
      $top_10_revenue_products[] = (float) $product['predicted_revenue'];
  }
// Extract product names for the labels
$top_10_qty_product_names = [];
foreach ($sales_forecast['top_10_qty_products'] as $product) {
    $top_10_qty_product_names[] = $product['product_id']; // Assuming 'name' is the key for product names
}

// Do the same for the top 10 revenue products
$top_10_revenue_product_names = [];
foreach ($sales_forecast['top_10_revenue_products'] as $product) {
    $top_10_revenue_product_names[] = $product['product_id']; // Assuming 'name' is the key for product names
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
    .chart-container {
      width: 40%;
      height: 100%;
      
    }

    #salesForecastChart,
    #predictedSalesQtyChart,
    #predictedSalesCountChart,
    #predictedRevenueChart,
    #top10QtyProductsChart,
    #top10RevenueProductsChart,

    {
      width: 100% !important;
      height: 400px; /* You can adjust this as per your requirement */
    }
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
        <p>Batangas Pet Shop Republic.</p>
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



  <!-- Update to remove tables and enhance chart designs -->
  <div class="bg-white p-3 rounded-lg shadow-md mt-4">
    <!-- Wrapper for all charts -->
    <div class="flex flex-wrap justify-between space-x-2 space-y-2">

      <!-- Sales Forecast Chart -->
      <div class="chart-container w-full sm:w-1/2">
        <canvas id="salesForecastChart"></canvas>
      </div>


      <!-- Predicted Sales Count -->
      <div class="chart-container w-full sm:w-1/2">
        <canvas id="predictedSalesCountChart"></canvas>
      </div>

      <!-- Predicted Revenue -->
      <div class="chart-container w-full sm:w-1/2">
        <canvas id="predictedRevenueChart"></canvas>
      </div>

      <!-- Top 10 Products by Quantity -->
      <div class="chart-container w-full sm:w-1/2">
        <canvas id="top10QtyProductsChart"></canvas>
      </div>

      <!-- Top 10 Products by Revenue -->
      <div class="chart-container w-full sm:w-1/2">
        <canvas id="top10RevenueProductsChart"></canvas>
      </div>
    </div>
  </div>





  <script>
// Common chart options with customizable Y-axis label
const commonOptions = {
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        font: {
          family: "'Roboto', sans-serif",
          weight: 'bold',
          size: 14,
        }
      }
    },
    tooltip: {
      mode: 'index',
      intersect: false,
      backgroundColor: 'rgba(0,0,0,0.7)',
      titleColor: 'white',
      bodyColor: 'white',
    }
  },
  scales: {
    x: {
      title: {
        display: true,
        text: 'Quarter',
        font: { size: 14, weight: 'bold' },
      },
      grid: { color: 'rgba(0, 0, 0, 0.1)' },
    },
    y: {
      grid: { color: 'rgba(0, 0, 0, 0.1)' },
      ticks: {
        beginAtZero: true,
      }
    }
  }
};

// Update Y-axis label for individual charts after creation
function updateYAxisLabel(chart, customLabel) {
  chart.options.scales.y.title.text = customLabel;
  chart.update();
}

// Sales Forecast Chart with custom Y-axis label
const salesForecastCtx = document.getElementById('salesForecastChart').getContext('2d');
const salesForecastChart = new Chart(salesForecastCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($quarters); ?>,
    datasets: [{
      label: 'Predicted Quantity Sold',
      data: <?php echo json_encode($predicted_sales); ?>,
      borderColor: '#2D9CDB',
      backgroundColor: 'rgba(45, 156, 219, 0.2)',
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Sales (Qty)', // Custom label for this chart
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});



// Predicted Sales Count Chart with custom Y-axis label
const predictedSalesCountCtx = document.getElementById('predictedSalesCountChart').getContext('2d');
const predictedSalesCountChart = new Chart(predictedSalesCountCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($quarters); ?>,
    datasets: [{
      label: 'Predicted Sales Count',
      data: <?php echo json_encode($predicted_sales_count); ?>,
      borderColor: '#FF7043',
      backgroundColor: 'rgba(255, 112, 67, 0.2)',
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Sales Count (Order)', // Custom label for this chart
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});

// Predicted Revenue Chart with custom Y-axis label
const predictedRevenueCtx = document.getElementById('predictedRevenueChart').getContext('2d');
const predictedRevenueChart = new Chart(predictedRevenueCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($quarters); ?>,
    datasets: [{
      label: 'Predicted Revenue',
      data: <?php echo json_encode($predicted_revenue); ?>,
      borderColor: '#FFEB3B',
      backgroundColor: 'rgba(255, 235, 59, 0.2)',
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Revenue', // Custom label for this chart
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});


// Top 10 Products by Quantity
const top10QtyCtx = document.getElementById('top10QtyProductsChart').getContext('2d');
const top10QtyProductsChart = new Chart(top10QtyCtx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($sales_forecast['top_10_qty_products']); ?>, // Product names from the API
    datasets: [{
      label: 'Top 10 Products by Quantity',
      data: <?php echo json_encode($top_10_qty_products); ?>, // Quantity data from the API
      backgroundColor: '#FF5722',
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Product Sold (Qty)', // Custom label for this chart
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});

// Top 10 Products by Revenue
const top10RevenueCtx = document.getElementById('top10RevenueProductsChart').getContext('2d');
const top10RevenueProductsChart = new Chart(top10RevenueCtx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($sales_forecast['top_10_revenue_products']); ?>, // Product names from the API
    datasets: [{
      label: 'Top 10 Products by Revenue',
      data: <?php echo json_encode($top_10_revenue_products); ?>, // Revenue data from the API
      backgroundColor: '#FFEB3B',
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Revenue (₱)', // Custom label for this chart
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});
// Update Y-axis label for Top 10 Products by Revenue Chart
updateYAxisLabel(top10RevenueProductsChart, 'Product Revenue');
</script>



  </body>
  </html>

  <?php include_once('layouts/footer.php'); ?>
  </body>
  </html>
