<?php
$page_title = 'Sales Report';
$results = '';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);
?>
<?php
if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if (empty($errors)) {
        $start_date = remove_junk($db->escape($_POST['start-date']));
        $end_date = remove_junk($db->escape($_POST['end-date']));
        $results = find_sale_by_dates($start_date, $end_date);
    } else {
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    }
} else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
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

    <style>
        @media print {
            body {
                font-size: 12pt;
                margin: 0;
                padding: 0;
            }

            .print-page {
                page-break-before: always;
                width: auto;
                margin: auto;
            }

            .no-print {
                display: none;
            }
        }

        .print-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .report-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4A5568;
        }

        .summary-table th, .summary-table td {
            border: 1px solid #E2E8F0;
        }

        .summary-table th {
            background-color: #EDF2F7;
            font-weight: bold;
        }

        .summary-table td {
            background-color: #F7FAFC;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="no-print flex justify-end p-4">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Print Report</button>
    </div>

    <div class="print-page bg-white shadow-lg rounded-lg p-6">
        <?php if ($results): ?>
            <div class="report-title">
                <h1>Sales Report</h1>
                <p class="text-gray-700"><?php echo isset($start_date) ? $start_date : ''; ?> to <?php echo isset($end_date) ? $end_date : ''; ?></p>
            </div>
            <table class="w-full summary-table border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Product Title</th>
                        <th class="px-4 py-2 text-right">Cost Price</th>
                        <th class="px-4 py-2 text-right">Selling Price</th>
                        <th class="px-4 py-2 text-right">Total Qty</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo remove_junk($result['date']); ?></td>
                            <td class="px-4 py-2"><?php echo remove_junk(ucfirst($result['name'])); ?></td>
                            <td class="px-4 py-2 text-right"><?php echo remove_junk($result['buy_price']); ?></td>
                            <td class="px-4 py-2 text-right"><?php echo remove_junk($result['sale_price']); ?></td>
                            <td class="px-4 py-2 text-right"><?php echo remove_junk($result['total_sales']); ?></td>
                            <td class="px-4 py-2 text-right"><?php echo remove_junk($result['total_saleing_price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-semibold">
                        <td colspan="4" class="px-4 py-2 text-right">Grand Total</td>
                        <td colspan="2" class="px-4 py-2 text-right">₱<?php echo number_format(total_price($results)[0], 2); ?></td>
                    </tr>
                    <tr class="font-semibold">
                        <td colspan="4" class="px-4 py-2 text-right">Profit</td>
                        <td colspan="2" class="px-4 py-2 text-right">₱<?php echo number_format(total_price($results)[1], 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <div class="text-center text-red-500">
                <p>Sorry, no sales have been found.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($db)) { $db->db_disconnect(); } ?>
</body>

</html>
