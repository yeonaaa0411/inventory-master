<?php
require_once('includes/load.php');

/* Function for find all database table rows by table name */
function find_all($table) {
   global $db;
   if(tableExists($table)) {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

/* Function for Perform queries */
/* Function for Perform queries */
function find_by_sql($sql) {
  global $db;
  $result = $db->query($sql);
  if ($result === false) {
      return []; // Return an empty array on query failure
  }
  $result_set = $db->while_loop($result);
  return is_array($result_set) ? $result_set : [];
}




/* Function for Find data from table by id */
/* Function for Find data from table by id */
function find_by_id($table, $id) {
  global $db;
  $id = (int)$id;
  if (tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
    if ($sql) {
      $result = $db->fetch_assoc($sql);
      // Ensure result is not null or empty before returning
      if ($result && is_array($result)) {
        return $result;
      } else {
        // Return null if no rows are found or result is not an array
        return null;
      }
    } else {
      // Return null if query fails
      return null;
    }
  }
  // Return null if table does not exist
  return null;
}




/* Function for Delete data from table by id */
function delete_by_id($table, $id) {
  global $db;
  if(tableExists($table)) {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}

/* Function for Count id  By table name */
function count_by_id($table) {
  global $db;
  if(tableExists($table)) {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
    return $result ? $db->fetch_assoc($result) : null;
  }
}

/* Function for last id  By table name */
function last_id($table) {
  global $db;
  if(tableExists($table)) {
    $sql    = "SELECT id FROM ".$db->escape($table) . " ORDER BY id DESC LIMIT 1";
    $result = $db->query($sql);
    return $result ? $db->fetch_assoc($result) : null;
  }
}

/* Determine if database table exists */
function tableExists($table) {
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
  if($table_exit) {
    return $db->num_rows($table_exit) > 0;
  }
  return false;
}

/* Login with the data provided in $_POST */

  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
 function authenticate_v2($username='', $password='') {
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if($db->num_rows($result)){
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if($password_request === $user['password'] ){
      return $user;
    }
  }
 return false;
}
function authenticate($username = '', $password = '') {
    global $db;

    // Sanitize inputs
    $username = $db->escape($username);
    $password = $db->escape($password);

    // SQL query to fetch the user data
    $sql = sprintf("SELECT id, username, password, user_level FROM users WHERE username = '%s' LIMIT 1", $username);
    $result = $db->query($sql);

    // Check if user exists
    if($db->num_rows($result)) {
        $user = $db->fetch_assoc($result);

        // Compare entered password with the stored hashed password
        $password_request = sha1($password);

        // If passwords match, return user data
        if($password_request === $user['password']) {
            return $user;  // Return the entire user array, not just the id
        }
    }

    // Return false if authentication fails
    return false;
}



  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
function find_all_user() {
    global $db;
    $results = array();
    $sql = "SELECT u.id, u.name, u.username, u.user_level, u.status, u.last_login, "; // Added last_login
    $sql .= "g.group_name "; // Fixed the missing space
    $sql .= "FROM users u ";
    $sql .= "LEFT JOIN user_groups g ON g.group_level = u.user_level ORDER BY u.name ASC";
    $result = find_by_sql($sql);
    return $result;
}


  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}


  /*--------------------------------------------------------------*/
  /* Function to log the action of a user
  /*--------------------------------------------------------------*/

 function logAction($user_id, $remote_ip, $action)
	{
		global $db;
    $date = make_date();
  $sql  = "INSERT INTO log (user_id,remote_ip,action,date)";
  $sql .= " VALUES ('{$user_id}','{$remote_ip}','{$action}','{$date}')";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level) {
    global $db;
    $sql = "SELECT group_level, group_status FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
  
    // Check if the result is valid and contains rows
    if ($db->num_rows($result) > 0) {
      return $db->fetch_assoc($result);  // Return the full group data (including group_status)
    } else {
      return null;  // Return null if no group is found
    }
  }
  
  
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
  function page_require_level($require_level) {
    global $session;
    $current_user = current_user();

    // Check if the current user is null
    if (!$current_user) {
        $session->msg('d', 'You must be logged in to view this page.');
        redirect('index.php', false);
    }

    $login_level = find_by_groupLevel($current_user['user_level']);
    
    // if user not logged in
    if (!$session->isUserLoggedIn(true)) {
        $session->msg('d', 'Please login...');
        redirect('index.php', false);
    }
    // if group status is deactivated (0) and login_level is not null
    elseif ($login_level && $login_level['group_status'] === '0') {
        $session->msg('d', 'This level user has been banned!');
        redirect('admin.php', false);
    }
    // checking if user level is less than or equal to the required level
    elseif ($current_user['user_level'] <= (int)$require_level) {
        return true;
    } else {
        $session->msg("d", "Sorry! You don't have permission to view the page.");
        redirect('add_order.php', false);
    } 
}


   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with category  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS category,m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.category_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product by category
  /*--------------------------------------------------------------*/

   function find_products_by_category($cat){
     global $db;
     $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS category,m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.category_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" WHERE c.id = '{$cat}'";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);
   }

   function filter_products_by_category($category_id) {
    global $db;
    $sql  = "SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.media_id, p.date, c.name AS category, m.file_name AS image ";
    $sql .= "FROM products p ";
    $sql .= "LEFT JOIN categories c ON c.id = p.category_id ";
    $sql .= "LEFT JOIN media m ON m.id = p.media_id ";
    $sql .= "WHERE c.id = '{$category_id}' ";
    $sql .= "ORDER BY p.id ASC";
    return find_by_sql($sql);
}




  /*--------------------------------------------------------------*/
  /* Function for Increase product quantity
  /*--------------------------------------------------------------*/
  function increase_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity +'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

  /*--------------------------------------------------------------*/
  /* Function for Decrease product quantity
  /*--------------------------------------------------------------*/
  function decrease_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS category,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.category_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sales(){
   global $db;
   $sql  = "SELECT s.id,s.order_id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN orders o ON s.order_id = o.id";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }

 /*--------------------------------------------------------------*/
 /* Function for find all orders
 /*--------------------------------------------------------------*/
 function find_all_orders(){
   global $db;
   $sql  = "SELECT o.id,o.sales_id,o.date";
   $sql .= " FROM orders o";
   $sql .= " LEFT JOIN sales s ON s.id = o.sales_id";
   $sql .= " ORDER BY o.date DESC";
   return find_by_sql($sql);
 }

 /*--------------------------------------------------------------*/
 /* Function for find sales by order_id
 /*--------------------------------------------------------------*/
 function find_sales_by_order_id($id) {
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN orders o ON s.order_id = o.id";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " WHERE s.order_id = " . $db->escape((int)$id);
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }




 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name, s.product_id,";
  $sql .= " SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT(s.date, '%c'), s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c') ASC";
  return find_by_sql($sql);
}
