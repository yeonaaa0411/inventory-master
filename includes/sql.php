<?php
require_once('includes/load.php');

/* Function for finding all database table rows by table name */
function find_all($table) {
   global $db;
   if(tableExists($table)) {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

/* Function for performing queries */
function find_by_sql($sql) {
  global $db;
  $result = $db->query($sql);
  if ($result === false) {
    return false; // Handle errors with SQL query
  }
  $result_set = $db->while_loop($result);
  return $result_set;
}

/* Function for finding data from table by id */
function find_by_id($table, $id) {
  global $db;
  $id = (int)$id;
  if(tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
    if ($sql) {
      $result = $db->fetch_assoc($sql);
      return $result ? $result : null;
    } else {
      return null;
    }
  }
  return null; // Add a default return in case table does not exist
}

/* Function for deleting data from table by id */
function delete_by_id($table, $id) {
  global $db;
  if(tableExists($table)) {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
  return false;
}

/* Function for counting ids by table name */
function count_by_id($table) {
  global $db;
  if(tableExists($table)) {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
    return $result ? $db->fetch_assoc($result) : null;
  }
  return null;
}

/* Function for finding the last id in a table */
function last_id($table) {
  global $db;
  if(tableExists($table)) {
    $sql    = "SELECT id FROM ".$db->escape($table) . " ORDER BY id DESC LIMIT 1";
    $result = $db->query($sql);
    return $result ? $db->fetch_assoc($result) : null;
  }
  return null;
}

/* Check if a database table exists */
function tableExists($table) {
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
  if($table_exit) {
    return $db->num_rows($table_exit) > 0;
  }
  return false;
}

/* Login with the data provided in $_POST */
function authenticate($username = '', $password = '') {
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if($password_request === $user['password']) {
      return $user['id'];
    }
  }
  return false;
}

/* Find the current logged-in user by session id */
function current_user(){
  static $current_user;
  global $db;
  if(!$current_user && isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $current_user = find_by_id('users', $user_id);
  }
  return $current_user;
}

/* Logging user action */
function logAction($user_id, $remote_ip, $action) {
  global $db;
  $date = make_date();
  $sql  = "INSERT INTO log (user_id, remote_ip, action, date)";
  $sql .= " VALUES ('{$user_id}', '{$remote_ip}', '{$action}', '{$date}')";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1);
}

/* Check if a group name exists */
function find_by_groupName($val) {
  global $db;
  $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return($db->num_rows($result) === 0);
}

/* Other utility functions as needed */
