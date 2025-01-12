<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);

 


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
    $recent_products = find_recent_product_added('10');
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





  </body>
  </html>

  <?php include_once('layouts/footer.php'); ?>
  </body>
  </html>