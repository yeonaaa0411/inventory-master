<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);

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
  
  // Loop through the forecast data and build the quarters array
  foreach ($sales_forecast['predictions'] as $data) {
      $quarter_label = "Q" . $data['quarter'] . " " . $data['year']; // Combine quarter and year for display
      
      // Check if the quarter label already exists to avoid duplication
      if (!in_array($quarter_label, $quarters)) {
          $quarters[] = $quarter_label;
      }
      
      $predicted_sales[] = (float) $data['predicted_qty'];
      $predicted_sales_count[] = (float) $data['predicted_sales_count'];
      $predicted_revenue[] = (float) $data['predicted_revenue'];
  }
  

  // Top 10 Products
  foreach ($sales_forecast['top_10_qty_products'] as $product) {
      $top_10_qty_products[] = (float) $product['predicted_qty'];
  }

  foreach ($sales_forecast['top_10_revenue_products'] as $product) {
      $top_10_revenue_products[] = (float) $product['predicted_revenue'];
  }
// Extract product names for the labels (use 'name' instead of 'product_id')
$top_10_qty_product_names = [];
foreach ($sales_forecast['top_10_qty_products'] as $product) {
    // Ensure we use 'product_name' for the chart labels
    if (isset($product['product_name'])) {
        $top_10_qty_product_names[] = $product['product_name']; // Correctly use 'product_name'
    } else {
        // Fallback to use 'product_id' if 'product_name' is missing
        $top_10_qty_product_names[] = "Unknown Product ID: " . $product['product_id'];
    }
}

// Do the same for the top 10 revenue products
$top_10_revenue_product_names = [];
foreach ($sales_forecast['top_10_revenue_products'] as $product) {
    if (isset($product['product_name'])) {
        $top_10_revenue_product_names[] = $product['product_name']; // Correctly use 'product_name'
    } else {
        // Fallback if 'product_name' is missing
        $top_10_revenue_product_names[] = "Unknown Product ID: " . $product['product_id'];
    }
}


// Get the top 1 product with most quantity to be sold
$top_1_qty_product = null;
$top_1_qty = 0;

foreach ($sales_forecast['top_10_qty_products'] as $product) {
    if ((float) $product['predicted_qty'] > $top_1_qty) {
        $top_1_qty = (float) $product['predicted_qty'];
        $top_1_qty_product = $product['product_name'];
    }
}

// Get the top 1 product that will generate the most revenue
$top_1_revenue_product = null;
$top_1_revenue = 0;

foreach ($sales_forecast['top_10_revenue_products'] as $product) {
    if ((float) $product['predicted_revenue'] > $top_1_revenue) {
        $top_1_revenue = (float) $product['predicted_revenue'];
        $top_1_revenue_product = $product['product_name'];
    }
}

define('THRESHOLD', 100);  // Adjust the threshold value as needed

// Extracting the slowest moving products (those with the lowest predicted quantities)
$slow_moving_products = [];
foreach ($sales_forecast['predictions'] as $data) {
    if ($data['predicted_qty'] < THRESHOLD) {  // Compare with the threshold constant
        $slow_moving_products[] = [
            'product_name' => $data['product_name'],
            'predicted_qty' => (float) $data['predicted_qty']
        ];
    }
}

// Sort slow-moving products by predicted quantity in ascending order (lowest first)
usort($slow_moving_products, function($a, $b) {
  return $a['predicted_qty'] - $b['predicted_qty']; // Sort by lowest quantity
});

// Get the top 10 slowest-moving products
$top_10_slow_moving = array_slice($slow_moving_products, 0, 10);


// Extract product names and predicted quantities
$slow_moving_product_names = [];
$slow_moving_quantities = [];
foreach ($top_10_slow_moving as $product) {
    $slow_moving_product_names[] = $product['product_name'];
    $slow_moving_quantities[] = $product['predicted_qty'];
}

// Ensure slow_moving_product_names and quantities are correctly populated
if (isset($sales_forecast['slow_moving_products']) && !empty($sales_forecast['slow_moving_products'])) {
  foreach ($sales_forecast['slow_moving_products'] as $product) {
      $slow_moving_product_names[] = $product['product_name'];
      $slow_moving_quantities[] = (float) $product['predicted_qty'];
  }
} else {
  // Optionally, you can set a default message or leave the arrays empty
  echo "No data available for slow-moving products.";
}
// Example of actual sales data (replace this with your actual data source)
$actual_sales = [
  ["quarter" => "Q4 2023", "actual_qty" => 10343, "actual_sales_count" => 1275, "actual_revenue" => 318662],
  ["quarter" => "Q1 2024", "actual_qty" => 10972, "actual_sales_count" => 1347, "actual_revenue" => 331858],
  ["quarter" => "Q2 2024", "actual_qty" => 10432, "actual_sales_count" => 1404, "actual_revenue" => 338469],
  ["quarter" => "Q3 2024", "actual_qty" => 12246, "actual_sales_count" => 1523, "actual_revenue" => 385790],
  ["quarter" => "Q4 2024", "actual_qty" => 8413, "actual_sales_count" => 975, "actual_revenue" => 296922]
];

// Extract actual data for the chart
$actual_sales_qty = [];
$actual_sales_count = [];
$actual_revenue = [];

foreach ($actual_sales as $data) {
  $actual_sales_qty[] = (float) $data['actual_qty'];
  $actual_sales_count[] = (float) $data['actual_sales_count'];
  $actual_revenue[] = (float) $data['actual_revenue'];
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php if($user['user_level'] == 1): ?>
    <div class="bg-gradient-to-r from-green-400 to-green-600 p-6 rounded-xl shadow-lg text-white flex items-center transition-transform duration-300 transform hover:scale-105">
        <i class="glyphicon glyphicon-user text-6xl"></i>
        <div class="ml-6 flex flex-col justify-center">
            <h2 class="text-4xl font-extrabold"><?php echo $c_user['total']; ?></h2>
            <p class="text-xl font-medium">Users</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-gradient-to-r from-red-400 to-red-600 p-6 rounded-xl shadow-lg text-white flex items-center transition-transform duration-300 transform hover:scale-105">
        <i class="glyphicon glyphicon-list text-6xl"></i>
        <div class="ml-6 flex flex-col justify-center">
            <h2 class="text-4xl font-extrabold"><?php echo $c_categorie['total']; ?></h2>
            <p class="text-xl font-medium">Categories</p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-6 rounded-xl shadow-lg text-white flex items-center transition-transform duration-300 transform hover:scale-105">
        <i class="glyphicon glyphicon-shopping-cart text-6xl"></i>
        <div class="ml-6 flex flex-col justify-center">
            <h2 class="text-4xl font-extrabold"><?php echo $c_product['total']; ?></h2>
            <p class="text-xl font-medium">Products</p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 p-6 rounded-xl shadow-lg text-white flex items-center transition-transform duration-300 transform hover:scale-105">
        <span class="text-6xl font-extrabold">₱</span>
        <div class="ml-6 flex flex-col justify-center">
            <h2 class="text-4xl font-extrabold"><?php echo $c_sale['total']; ?></h2>
            <p class="text-xl font-medium">Total Sales</p>
        </div>
    </div>
</div>


  <!-- Welcome Panel -->
  <script>

  function closePanel() {
    var panel = document.getElementById("myDIV");
    panel.classList.toggle('hidden');
  }
</script>

<div class="mt-6" id="myDIV" class="transition-all duration-300">
  <div class="bg-white p-6 rounded-xl shadow-xl w-full mx-auto">
    <div class="flex justify-end">
      <button onclick="closePanel();" class="bg-red-600 text-white rounded-full p-2 hover:bg-red-700 transition-colors">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="text-center">
      <h3 class="text-3xl font-semibold text-gray-800 mb-2">Welcome Back!</h3>
      <p class="text-3g text-gray-600">Batangas Pet Shop Republic</p>
    </div>
  </div>
</div>


 <!-- Highest Selling Products, Latest Sales, Recently Added Products -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
  <!-- Highest Selling Products -->
  <div class="bg-white p-8 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
    <div class="font-bold text-3xl mb-6 flex items-center">
      <i class="glyphicon glyphicon-th text-3xl text-blue-500 mr-3"></i>
      Highest Selling Products
    </div>
    <table class="table w-full text-lg text-gray-700">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-3 text-left text-xl">Product</th>
          <th class="py-3 text-center text-xl">Total Sold</th>
          <th class="py-3 text-center text-xl">Total Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products_sold as $product_sold): ?>
        <tr>
          <td class="py-4 px-3 text-lg"><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
          <td class="py-4 px-3 text-center text-lg"><?php echo (int)$product_sold['totalSold']; ?></td>
          <td class="py-4 px-3 text-center text-lg"><?php echo (int)$product_sold['totalQty']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Latest Sales -->
  <div class="bg-white p-8 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
    <div class="font-bold text-3xl mb-6 flex items-center">
      <i class="glyphicon glyphicon-th text-3xl text-purple-500 mr-3"></i>
      Latest Sales
    </div>
    <table class="table w-full text-lg text-gray-700">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-3 text-left text-xl">#</th>
          <th class="py-3 text-left text-xl">Product</th>
          <th class="py-3 text-center text-xl">Date</th>
          <th class="py-3 text-right text-xl">Total Sale</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_sales as $recent_sale): ?>
        <tr>
          <td class="py-4 px-3 text-lg"><?php echo count_id(); ?></td>
          <td class="py-4 px-3 text-lg">
            <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>" class="text-blue-600 hover:text-blue-800">
              <?php echo remove_junk(first_character($recent_sale['name'])); ?>
            </a>
          </td>
          <td class="py-4 px-3 text-center text-lg"><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
          <td class="py-4 px-3 text-right text-lg">₱<?php echo number_format((float)$recent_sale['price'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Recently Added Products -->
  <div class="bg-white p-8 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
    <div class="font-bold text-3xl mb-6 flex items-center">
      <i class="glyphicon glyphicon-th text-3xl text-yellow-500 mr-3"></i>
      Recently Added Products
    </div>
    <table class="table w-full text-lg text-gray-700">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-3 text-left text-xl">Product</th>
          <th class="py-3 text-left text-xl">Category</th>
          <th class="py-3 text-right text-xl">Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_products as $recent_product): ?>
        <tr>
          <td class="py-4 px-3 text-lg">
            <a href="view_product.php?id=<?php echo (int)$recent_product['id']; ?>" class="text-blue-600 hover:text-blue-800">
              <?php echo remove_junk(first_character($recent_product['name'])); ?>
            </a>
          </td>
          <td class="py-4 px-3 text-lg"><?php echo remove_junk(first_character($recent_product['category'])); ?></td>
          <td class="py-4 px-3 text-right text-lg">₱<?php echo number_format((float)$recent_product['sale_price'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>




  <div class="bg-white p-3 rounded-lg shadow-md mt-4">
  <!-- Wrapper for all charts -->
  <div class="flex flex-wrap justify-between space-x-2 space-y-2">
<!-- Predicted Sales and Revenue Dashboard -->
<div class="bg-white p-6 rounded-xl shadow-lg mt-6 w-full">
  <div class="flex justify-center items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 text-center">Predicted Sales & Revenue</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
    <!-- Predicted Sales Count -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-2xl font-bold">Predicted Sales Count (Month)</h3> <!-- Increased font size and weight -->
      <p class="text-4xl font-semibold mt-2"><?php echo number_format($predicted_sales_count[0]); ?> </p>
    </div>
    <!-- Predicted Revenue -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-2xl font-bold">Predicted Revenue (Quarter)</h3> <!-- Increased font size and weight -->
      <p class="text-4xl font-semibold mt-2">₱<?php echo number_format($predicted_revenue[0], 2); ?></p>
    </div>
  </div>
  <!-- Top 1 Product by Quantity & Revenue -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
    <!-- Top 1 Product by Revenue -->
    <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-2xl font-bold">Top 1 Product by Revenue (Year)</h3> <!-- Increased font size and weight -->
      <p class="text-2xl font-semibold mt-2"><?php echo $top_1_revenue_product ? $top_1_revenue_product : 'No data available'; ?></p>
      <p class="text-4xl font-semibold mt-2">₱<?php echo number_format($top_1_revenue, 2); ?></p>
    </div>
    <!-- Top 1 Product by Quantity -->
    <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
      <h3 class="text-2xl font-bold">Top 1 Product Must Sold (Year)</h3> <!-- Increased font size and weight -->
      <p class="text-2xl font-semibold mt-2"><?php echo $top_1_qty_product ? $top_1_qty_product : 'No data available'; ?></p>
      <p class="text-4xl font-semibold mt-2"><?php echo number_format($top_1_qty, 0); ?> Units</p>
    </div>
  </div>
</div>


    <!-- Sales Forecast Chart -->
    <div class="chart-container w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 p-4">
      <canvas id="salesForecastChart"></canvas>
    </div>

    <!-- Predicted Sales Count -->
    <div class="chart-container w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 p-4">
      <canvas id="predictedSalesCountChart"></canvas>
    </div>

    <!-- Predicted Revenue -->
    <div class="chart-container w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 p-4">
      <canvas id="predictedRevenueChart"></canvas>
    </div>

    <!-- Top 10 Products by Quantity -->
    <div class="chart-container w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 p-4">
       <canvas id="top10QtyProductsChart"></canvas>
    </div>

    <!-- Top 10 Products by Revenue -->
    <div class="chart-container w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 p-4">
      <canvas id="top10RevenueProductsChart"></canvas>
    </div>

        <!-- Top 10 Slow-Moving Products -->
        <div class="chart-container w-full sm:w-1/2 lg:w-1/2 xl:w-1/2 p-4">
           <canvas id="slowMovingProductsChart"></canvas>
        </div>

    </div>
  </div>
</div>

<script>
// Common chart options with customizable Y-axis label and shadow effects
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
  },
  elements: {
    line: {
      borderWidth: 3, // Thicker line for better visibility
    },
    point: {
      radius: 5, // Slightly larger points for clarity
      hoverRadius: 8, // Hover effect for points
    }
  }
};

// Apply hover effect and shadow on hover for all charts
function applyHoverEffect(chart) {
  chart.canvas.addEventListener('mouseover', () => {
    chart.canvas.style.transform = 'scale(1.05)';
    chart.canvas.style.transition = 'transform 0.3s ease';
  });

  chart.canvas.addEventListener('mouseout', () => {
    chart.canvas.style.transform = 'scale(1)';
  });
}

// Sales Forecast Chart with both predicted and actual data
const salesForecastCtx = document.getElementById('salesForecastChart').getContext('2d');
const salesForecastChart = new Chart(salesForecastCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($quarters); ?>,
    datasets: [
      {
        label: 'Predicted Sales Quantity',
        data: <?php echo json_encode($predicted_sales); ?>,
        borderColor: '#2D9CDB',
        backgroundColor: 'rgba(45, 156, 219, 0.2)',
        fill: true,
        tension: 0.4
      },
      {
        label: 'Actual Quantity Sold',
        data: <?php echo json_encode($actual_sales_qty); ?>,
        borderColor: '#FF5733', // Red color for actual data
        backgroundColor: 'rgba(255, 87, 51, 0.2)', // Light red for actual data
        fill: true,
        tension: 0.4
      }
    ]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Sales (Qty)',
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});
applyHoverEffect(salesForecastChart);

// Predicted Sales Count Chart with shadow and hover effect
const predictedSalesCountCtx = document.getElementById('predictedSalesCountChart').getContext('2d');
const predictedSalesCountChart = new Chart(predictedSalesCountCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($quarters); ?>,
    datasets: [
      {
        label: 'Predicted Sales Count',
        data: <?php echo json_encode($predicted_sales_count); ?>,
        borderColor: '#2D9CDB',
        backgroundColor: 'rgba(45, 156, 219, 0.2)',
        fill: true,
        tension: 0.4
      },
      {
        label: 'Actual Sales Count',
        data: <?php echo json_encode($actual_sales_count); ?>,
        borderColor: '#FF5733', // Red color for actual data
        backgroundColor: 'rgba(255, 87, 51, 0.2)', // Light red for actual data
        fill: true,
        tension: 0.4
      }
    ]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Sales (Qty)',
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});
applyHoverEffect(predictedSalesCountChart);

// Predicted Revenue Chart with both predicted and actual data
const predictedRevenueCtx = document.getElementById('predictedRevenueChart').getContext('2d');
const predictedRevenueChart = new Chart(predictedRevenueCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($quarters); ?>,
    datasets: [
      {
        label: 'Predicted Revenue',
        data: <?php echo json_encode($predicted_revenue); ?>,
        borderColor: '#FFEB3B',
        backgroundColor: 'rgba(255, 235, 59, 0.2)',
        fill: true,
        tension: 0.4
      },
      {
        label: 'Actual Revenue',
        data: <?php echo json_encode($actual_revenue); ?>,
        borderColor: '#FF5733', // Red color for actual data
        backgroundColor: 'rgba(255, 87, 51, 0.2)', // Light red for actual data
        fill: true,
        tension: 0.4
      }
    ]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Revenue (₱)',
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});
applyHoverEffect(predictedRevenueChart);

// Top 10 Products by Quantity Chart with shadow and hover effect
var ctx1 = document.getElementById('top10QtyProductsChart').getContext('2d');
var top10QtyProductsChart = new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($top_10_qty_product_names); ?>,
    datasets: [{
      label: 'Top 10 Products Predicted Quantity Sales',
      data: <?php echo json_encode($top_10_qty_products); ?>,
      borderColor: '#4B5563',
      backgroundColor: 'rgba(75, 85, 99, 0.2)',
      borderWidth: 2,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      x: {
        ...commonOptions.scales.x,
        title: {
          display: true,
          text: 'Products',
          font: { size: 14, weight: 'bold' },
        }
      },
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Quantity Sold',
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});
applyHoverEffect(top10QtyProductsChart);

// Top 10 Products by Revenue Chart with shadow and hover effect
var ctx2 = document.getElementById('top10RevenueProductsChart').getContext('2d');
var top10RevenueProductsChart = new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($top_10_revenue_product_names); ?>,
    datasets: [{
      label: 'Top 10 Products Predicted Revenue',
      data: <?php echo json_encode($top_10_revenue_products); ?>,
      borderColor: '#F57C00',
      backgroundColor: 'rgba(245, 124, 0, 0.2)',
      borderWidth: 2,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      ...commonOptions.scales,
      x: {
        ...commonOptions.scales.x,
        title: {
          display: true,
          text: 'Products',
          font: { size: 14, weight: 'bold' },
        }
      },
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Revenue (₱)',
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});
applyHoverEffect(top10RevenueProductsChart);

// Slow Moving Products Chart with shadow and hover effect
var ctx = document.getElementById('slowMovingProductsChart').getContext('2d');

// Get the data and sort it from fewest to largest
var productsData = <?php echo json_encode(array_map(null, $slow_moving_product_names, $slow_moving_quantities)); ?>;

// Sort by quantities (ascending order)
productsData.sort(function(a, b) {
  return a[1] - b[1]; // Sort by the second element (quantities) in ascending order
});

// Slice to get top 5 products
var top5ProductNames = productsData.slice(0, 5).map(function(item) { return item[0]; });
var top5Quantities = productsData.slice(0, 5).map(function(item) { return item[1]; });

var slowMovingProductsChart = new Chart(ctx, {
  type: 'bar', // Use 'bar' type for a horizontal bar chart
  data: {
    labels: top5ProductNames, // Top 5 sorted product names
    datasets: [{
      label: 'Slow-Moving Products',
      data: top5Quantities, // Top 5 sorted quantities
      borderColor: '#2B8A3E',
      backgroundColor: 'rgba(43, 138, 62, 0.2)',
      borderWidth: 2,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    ...commonOptions,
    indexAxis: 'y', // This makes the chart horizontal
    scales: {
      ...commonOptions.scales,
      x: {
        ...commonOptions.scales.x,
        title: {
          display: true,
          text: 'Sales Count (Quarter)', // Custom x-axis title for this chart
          font: { size: 14, weight: 'bold' },
        }
      },
      y: {
        ...commonOptions.scales.y,
        title: {
          display: true,
          text: 'Products', // Custom y-axis title for this chart
          font: { size: 14, weight: 'bold' },
        }
      }
    }
  }
});

applyHoverEffect(slowMovingProductsChart);




</script>



  </body>
  </html>

  <?php include_once('layouts/footer.php'); ?>
  </body>
  </html>