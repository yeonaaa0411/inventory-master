<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// -----------------------------------------------------------------------
// DEFINE SEPERATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');
define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT')? null: define('SITE_ROOT', realpath(dirname(__FILE__)));
define("LIB_PATH_INC", SITE_ROOT.DS);

require_once(LIB_PATH_INC.'config.php');
require_once(LIB_PATH_INC.'functions.php');
require_once(LIB_PATH_INC.'session.php');
require_once(LIB_PATH_INC.'upload.php');
require_once(LIB_PATH_INC.'database.php');
require_once(LIB_PATH_INC.'sql.php');


// Check if the session variable 'user_id' is set
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Handle the case where user_id is not set
    $user_id = null; // or redirect to login page, show an error, etc.
}

$remote_ip = $_SERVER['REMOTE_ADDR'];
$action = $_SERVER['REQUEST_URI'];
$action = preg_replace('/^.+[\\\\\\/]/', '', $action);

// logging disabled ~ remove the comment "//" to enable
// logAction($user_id, $remote_ip, $action);
?>
