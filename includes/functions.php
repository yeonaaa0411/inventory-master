<?php
$errors = array();

/*--------------------------------------------------------------*/
/* Function for Remove escapes special
/* characters in a string for use in an SQL statement
/*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con, $str);
  return $escape;
}

/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}

/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-', " ", $str);
  $val = ucfirst($val);
  return $val;
}

/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val == ''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}

/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg = ''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}

/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}

/*--------------------------------------------------------------*/
/* Function for find out total sale price, cost price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
  $sum = 0;
  $sub = 0;
  $profit = 0;  // Initialize $profit
  foreach($totals as $total ){
    $sum += $total['total_saleing_price'];
    $sub += $total['total_buying_price'];
    $profit = $sum - $sub;
  }
  return array($sum, $profit);
}


/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('M j, Y, g:i:s a', strtotime($str));
     else
      return null;
}

/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date() {
  return date("Y-m-d H:i:s");
}
date_default_timezone_set('Asia/Manila');

/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}

/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str = '';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x = 0; $x < $length; $x++)
    $str .= $cha[mt_rand(0, strlen($cha))];
  return $str;
}

/*--------------------------------------------------------------*/
/* Function for Find product sales by month
/*--------------------------------------------------------------*/
function find_product_sales_by_month($product_id, $start_date, $end_date) {
  global $db;
  $sql = "SELECT DATE_FORMAT(date, '%Y-%m') as sale_month, SUM(qty) as total_sales ";
  $sql .= "FROM sales ";
  $sql .= "WHERE product_id = '{$product_id}' ";
  $sql .= "AND date BETWEEN '{$start_date}' AND '{$end_date}' ";
  $sql .= "GROUP BY sale_month ";
  $sql .= "ORDER BY sale_month DESC ";
  $result = $db->query($sql);

  $sales_data = [];
  while($row = $db->fetch_assoc($result)) {
      $sales_data[$row['sale_month']] = $row['total_sales'];
  }

  return $sales_data;
}
// This is a placeholder for your predictive analytics function.
// Replace with actual data fetching or calculations.
function get_sales_forecast($months = 2) {
  // Predict total sales for the next $months (e.g., 2 months)
  // Fetch past sales data from the database to calculate predictions
  $forecast = [];

  // Example: Use a predictive model or statistics for total sales prediction
  // Let's assume the model predicted a total sales value for the next 2 months.
  $predicted_sales = rand(50000, 100000);  // Randomized total sales prediction for 2 months

  // Predict for 2 months
  for ($i = 0; $i < $months; $i++) {
      $forecast[] = [
          'month' => date('F Y', strtotime("+$i month")), // Current month + i months
          'predicted_sales' => $predicted_sales,
      ];
  }

  return $forecast;
}


?>
