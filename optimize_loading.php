<?php
// Check if cached data exists and is fresh
$cache_file = 'cache/sales_forecast_cache.json';
$cache_lifetime = 3600; // Cache lifetime in seconds (1 hour)

if (file_exists($cache_file) && time() - filemtime($cache_file) < $cache_lifetime) {
    // If cached data exists and is still valid, read from cache
    $forecast = file_get_contents($cache_file);
} else {
    // Run the Flask API to fetch the sales forecast
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/predict_sales");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $forecast = curl_exec($ch);
    curl_close($ch);

    // Cache the result to a file
    file_put_contents($cache_file, $forecast);
}

$sales_forecast = json_decode($forecast, true);

// Pass the data to the frontend
$quarters = $sales_forecast['quarters'];
$predicted_sales = $sales_forecast['predicted_sales'];
$actual_sales_qty = $sales_forecast['actual_sales_qty'];
$predicted_sales_count = $sales_forecast['predicted_sales_count'];
$actual_sales_count = $sales_forecast['actual_sales_count'];
$predicted_revenue = $sales_forecast['predicted_revenue'];
$actual_revenue = $sales_forecast['actual_revenue'];
$top_10_qty_product_names = $sales_forecast['top_10_qty_product_names'];
$top_10_qty_products = $sales_forecast['top_10_qty_products'];
$top_10_revenue_product_names = $sales_forecast['top_10_revenue_product_names'];
$top_10_revenue_products = $sales_forecast['top_10_revenue_products'];
$slow_moving_product_names = $sales_forecast['slow_moving_product_names'];
$slow_moving_quantities = $sales_forecast['slow_moving_quantities'];
?>
