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
        /* Header Background Color */
        .custom-header {
            background-color: rgba(236, 253, 245, 1); /* Light green background */
        }

        /* Card Styling */
        .card {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        table th, table td {
            padding: 0.75rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        table th {
            background-color: #f9fafb;
            font-weight: bold;
        }

        table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Form Input Styling */
        .form-input {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-100">

    <?php include_once('layouts/header.php'); ?>

    <div class="flex justify-center mt-10">
        <div class="w-full sm:w-2/3 lg:w-1/2">
            <div class="card">
                <div class="custom-header p-6 border-b">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-2xl text-green-600"></i>
                        <strong class="text-3xl font-semibold text-gray-800">Generate Report</strong>
                    </div>
                </div>

                <div class="p-6">
                    <?php echo display_msg($msg); ?>

                    <form method="post" action="sale_report_process.php">
                        <div class="mb-6">
                            <label for="start-date" class="form-label">Start Date</label>
                            <input type="date" id="start-date" class="form-input" name="start-date" required>
                        </div>

                        <div class="mb-6">
                            <label for="end-date" class="form-label">End Date</label>
                            <input type="date" id="end-date" class="form-input" name="end-date" required>
                        </div>

                        <div class="flex justify-center mt-6">
                            <button type="submit" name="submit" class="btn-primary">Generate Report</button>
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
                            echo "<p class='text-center mt-4 font-semibold text-red-600'>No sales data found for the selected range.</p>";
                        } else {
                            echo "<table class='mt-4 w-full text-sm text-gray-700'>";
                            echo "<thead><tr><th>Month</th><th>Total Sales</th></tr></thead><tbody>";
                            foreach ($sales_data as $month => $total_sales) {
                                echo "<tr><td>{$month}</td><td>₱ {$total_sales}</td></tr>";
                            }
                            echo "</tbody></table>";
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
