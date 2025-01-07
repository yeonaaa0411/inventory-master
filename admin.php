<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);
  sleep(7);
  // Run the Flask API to fetch the sales forecast
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/predict_sales"); // Ensure this URL is correct
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $forecast = curl_exec($ch);
  curl_close($ch);



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
  /* Remove hover effect from the container */
  .chart-container {
    width: 40%;
    height: 100%;
  }

  /* Apply hover effect only on the chart canvas */
  .chart-container canvas {
    width: 100% !important;
    height: 400px !important;
    transition: transform 0.3s ease; /* Apply hover effect on canvas */
  }

  /* Hover effect only when hovering over the graph (canvas) */
  .chart-container canvas:hover {
    transform: scale(1.05); /* Make the graph grow slightly when hovered */
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

<!-- Predicted Sales & Revenue Panel -->
<div class="predicted-container bg-white p-8 rounded-xl shadow-lg mt-8 w-full">
    <div class="font-bold text-3xl mb-6 flex items-center justify-center">
        <i class="fas fa-chart-line text-3xl text-green-500 mr-3"></i>
        Predicted Sales & Revenue
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
        <!-- Predicted Sales Count -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <h3 class="text-2xl font-bold">Predicted Sales Count (Month)</h3>
            <p class="text-4xl font-semibold mt-2">1,408</p>
        </div>
        <!-- Predicted Revenue -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <h3 class="text-2xl font-bold">Predicted Revenue (Quarter)</h3>
            <p class="text-4xl font-semibold mt-2">₱334,533.00</p>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
        <!-- Top 1 Product by Revenue -->
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <h3 class="text-2xl font-bold">Top 1 Product by Revenue (Year)</h3>
            <p class="text-2xl font-semibold mt-2">15 gallon stand</p>
            <p class="text-4xl font-semibold mt-2">₱176,396.42</p>
        </div>
        <!-- Top 1 Product by Quantity -->
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 p-6 rounded-xl shadow-lg text-white text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <h3 class="text-2xl font-bold">Top 1 Product Must Sold (Year)</h3>
            <p class="text-2xl font-semibold mt-2">Super Worm</p>
            <p class="text-4xl font-semibold mt-2">41,226 Units</p>
        </div>
    </div>
</div>
<!-- Sales Forecast & Actual Data -->
<div class="bg-white p-8 rounded-xl shadow-lg mt-8 w-full">
    <div class="font-bold text-3xl mb-6 flex items-center justify-center">
        <i class="fas fa-chart-line text-3xl text-green-500 mr-3"></i>
        Sales Forecast & Actual Data
    </div>


    
    <div class="grid grid-cols-2 gap-8"> <!-- Changed to 2 columns per row -->
        <!-- Chart 1 -->
        <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="relative">
                <canvas id="salesForecastChart1" width="800" height="400"></canvas>
            </div>
        </div>

        <!-- Chart 2 -->
        <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="relative">
                <canvas id="salesForecastChart2" width="800" height="400"></canvas>
            </div>
        </div>

        <!-- Chart 3 -->
        <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="relative">
                <canvas id="salesForecastChart3" width="800" height="400"></canvas>
            </div>
        </div>
        
        <!-- Chart 4 -->
        <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="relative">
                <canvas id="salesForecastChart4" width="800" height="400"></canvas>
            </div>
        </div>
        
        <!-- Chart 5 -->
        <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="relative">
                <canvas id="salesForecastChart5" width="800" height="400"></canvas>
            </div>
        </div>

        <!-- Chart 6 (Horizontal Bar Chart) -->
        <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <div class="relative">
                <canvas id="salesForecastChart6" width="800" height="400"></canvas>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart 1
const salesForecastCtx1 = document.getElementById('salesForecastChart1').getContext('2d');
const salesForecastChart1 = new Chart(salesForecastCtx1, {
    type: 'line',
    data: {
        labels: ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024', 'Q1 2025'],
        datasets: [
            {
                label: 'Predicted Sales Quantity',
                data: [8884457, 9313087, 8965463, 7913087, 8487457],
                borderColor: '#2D9CDB',
                backgroundColor: 'rgba(45, 156, 219, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6  // Increase the size of the circles
            },
            {
                label: 'Actual Quantity Sold',
                data: [10343000, 10972000, 10432000, 8413000, 29],
                borderColor: '#FF5733',
                backgroundColor: 'rgba(255, 87, 51, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6  // Increase the size of the circles
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: { 
                title: { 
                    display: true, 
                    text: 'Quarters', 
                    font: { weight: 'bold' } 
                }
            },
            y: { 
                title: { 
                    display: true, 
                    text: 'Sales Quantity',
                    font: { weight: 'bold' }
                },
                ticks: { beginAtZero: true }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: { weight: 'bold' }
                }
            }
        }
    }
});

// Chart 2
const salesForecastCtx2 = document.getElementById('salesForecastChart2').getContext('2d');
const salesForecastChart2 = new Chart(salesForecastCtx2, {
    type: 'line',
    data: {
        labels: ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024', 'Q1 2025'],
        datasets: [
            {
                label: 'Predicted Sales Count',
                data: [1502, 1583, 1625, 1647, 1658],
                borderColor: '#2D9CDB',
                backgroundColor: 'rgba(45, 156, 219, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6 // Added point radius for visibility
            },
            {
                label: 'Actual Sales Count',
                data: [1275, 1347, 1404, 1523, 8],
                borderColor: '#FF5733',
                backgroundColor: 'rgba(255, 87, 51, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6 // Added point radius for visibility
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: { 
                title: { 
                    display: true, 
                    text: 'Quarters', 
                    font: { weight: 'bold' } 
                }
            },
            y: { 
                title: { 
                    display: true, 
                    text: 'Sales Count', 
                    font: { weight: 'bold' } 
                },
                ticks: { beginAtZero: true }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: { weight: 'bold' }
                }
            }
        }
    }
});


// Chart 3
const salesForecastCtx3 = document.getElementById('salesForecastChart3').getContext('2d');
const salesForecastChart3 = new Chart(salesForecastCtx3, {
    type: 'line',
    data: {
        labels: ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024', 'Q1 2025'],
        datasets: [
            {
                label: 'Predicted Revenue',
                data: [360757, 326792, 342807, 334029, 338877],
                borderColor: '#2D9CDB',
                backgroundColor: 'rgba(45, 156, 219, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6 // Added point radius for visibility
            },
            {
                label: 'Actual Revenue',
                data: [318662, 342858, 338469, 385790, 3486],
                borderColor: '#FF5733',
                backgroundColor: 'rgba(255, 87, 51, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6 // Added point radius for visibility
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: { 
                title: { 
                    display: true, 
                    text: 'Quarters', 
                    font: { weight: 'bold' } 
                }
            },
            y: { 
                title: { 
                    display: true, 
                    text: 'Revenue (₱)', 
                    font: { weight: 'bold' } 
                },
                ticks: { beginAtZero: true }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: { weight: 'bold' }
                }
            }
        }
    }
});


// Chart 4
const salesForecastCtx4 = document.getElementById('salesForecastChart4').getContext('2d');
const salesForecastChart4 = new Chart(salesForecastCtx4, {
    type: 'bar',
    data: {
        labels: ['15 galloon stand', 'Tweety Wood', 'Top Light', 'Glofish', 'Neontetra', 'Heater', '15 gallon tank', 'Ranch', 'Siamese Algae Eater', 'Cat Litter'],
        datasets: [
            {
                label: 'Top 10 Products Predicted Revenue',
                data: [186300, 172614, 73748, 60862, 60152, 56114, 53311, 37014, 36278, 35758],
                borderColor: '#FF6347', 
                backgroundColor: 'rgba(255, 99, 71, 0.2)', 
                borderWidth: 1,
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: { 
                    display: true, 
                    text: 'Products', 
                    font: { weight: 'bold' } 
                },
                ticks: {
                    autoSkip: false,
                }
            },
            y: {
                title: { 
                    display: true, 
                    text: 'Revenue (₱)', 
                    font: { weight: 'bold' } 
                },
                ticks: {
                    beginAtZero: true,
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: { weight: 'bold' }
                }
            }
        }
    }
});

// Chart 5 (Duplicate of Chart 4)
const salesForecastCtx5 = document.getElementById('salesForecastChart5').getContext('2d');
const salesForecastChart5 = new Chart(salesForecastCtx5, {
    type: 'bar',
    data: {
        labels: ['Super Worm', 'S. Molly', 'Feeders', 'Neontetra', 'Glofish', 'Moss Ball', 'Danio', 'Angel Fish', 'Rcs', 'Molly', 'Cat Litter'],
        datasets: [
            {
                label: 'Top 10 Products Predicted Quantity Sales',
                data: [39609, 1635, 1366, 1217, 1002, 943, 665, 596, 574, 448],
                borderColor: '#32CD32', 
                backgroundColor: 'rgba(50, 205, 50, 0.2)', 
                borderWidth: 1,
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: { 
                    display: true, 
                    text: 'Products', 
                    font: { weight: 'bold' } 
                },
                ticks: {
                    autoSkip: false,
                }
            },
            y: {
                title: { 
                    display: true, 
                    text: 'Quantity Sold (Year)', 
                    font: { weight: 'bold' } 
                },
                ticks: {
                    beginAtZero: true,
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: { weight: 'bold' }
                }
            }
        }
    }
});

// Chart 6 (Horizontal Bar Chart)
const salesForecastCtx6 = document.getElementById('salesForecastChart6').getContext('2d');
const salesForecastChart6 = new Chart(salesForecastCtx6, {
    type: 'bar',
    data: {
        labels: ['Branded File Snake', 'Humpy Head', 'Koi King', 'Top Filter', 'AC/DC Airpump 30h'],
        datasets: [
            {
                label: 'Predicted Slow-Moving Products',
                data: [3, 3, 17, 26, 27],
                borderColor: '#2D9CDB',
                backgroundColor: 'rgba(45, 156, 219, 0.2)',
                borderWidth: 1,
            }
        ]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        scales: {
            x: {
                title: { 
                    display: true, 
                    text: 'Sales Count (Quarter)', 
                    font: { weight: 'bold' } 
                },
                ticks: {
                    beginAtZero: true,
                }
            },
            y: {
                title: { 
                    display: true, 
                    text: 'Products', 
                    font: { weight: 'bold' } 
                },
                ticks: {
                    autoSkip: false,
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    font: { weight: 'bold' }
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
