<?php
$page_title = 'Generate Report';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .custom-header {
            background-color: #eaf5e9; /* Light green color */
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-left mt-10">
        <div class="w-full sm:w-2/3 lg:w-1/3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="custom-header p-6 border-b">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar mr-2" style="font-size: 20px;"></i>
                        <strong class="text-3xl font-bold">Generate Report</strong>
                    </div>
                </div>
                <div class="p-6">
                    <?php echo display_msg($msg); ?>
                    <form method="post" action="sale_report_process.php" class="clearfix">
                        <div class="mb-4">
                            <label for="start-date" class="block text-gray-700 font-bold mb-2">Start Date</label>
                            <input type="date" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="start-date" required>
                        </div>

                        <div class="mb-4">
                            <label for="end-date" class="block text-gray-700 font-bold mb-2">End Date</label>
                            <input type="date" class="form-control border border-gray-300 rounded-md px-4 py-2 w-full" name="end-date" required>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" name="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Generate Report</button>
                        </div>
                    </form>

                    <?php
                    // Process form submission
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start-date']) && isset($_POST['end-date'])) {
                        $start_date = $_POST['start-date'];
                        $end_date = $_POST['end-date'];

                        // Fetch sales data for the selected date range
                        $sales_data = find_product_sales_by_month(null, $start_date, $end_date);

                        if (empty($sales_data)) {
                            echo "<p class='text-center mt-4 font-bold text-red-600'>No sales data</p>";
                        } else {
                            echo "<table class='mt-4 w-full text-center'>";
                            echo "<tr><th>Month</th><th>Total Sales</th></tr>";
                            foreach ($sales_data as $month => $total_sales) {
                                echo "<tr><td>{$month}</td><td>₱ {$total_sales}</td></tr>";
                            }
                            echo "</table>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('layouts/footer.php'); ?>
</body>

</html>
