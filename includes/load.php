<?php
// -----------------------------------------------------------------------
// DEFINE SEPARATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');
define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT')? null: define('SITE_ROOT', realpath(dirname(__FILE__)));
define("LIB_PATH_INC", SITE_ROOT . DS);

// Include necessary files before starting the session
require_once(LIB_PATH_INC . 'config.php');
require_once(LIB_PATH_INC . 'functions.php');
require_once(LIB_PATH_INC . 'session.php');
require_once(LIB_PATH_INC . 'upload.php');
require_once(LIB_PATH_INC . 'database.php');
require_once(LIB_PATH_INC . 'sql.php');

// Start the session (make sure this is done before accessing any session variables)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variable 'user_id' is set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle the case where user_id is not set, e.g., redirect or show error
if ($user_id === null) {
    // Redirect to login page or handle accordingly
}

// Logging setup
$remote_ip = $_SERVER['REMOTE_ADDR'];
$action = $_SERVER['REQUEST_URI'];
$action = preg_replace('/^.+[\\\\\\/]/', '', $action);

// logging disabled ~ remove the comment "//" to enable
// logAction($user_id, $remote_ip, $action);
?>
