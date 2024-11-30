<?php
session_start(); // Ensure session is started
require_once('includes/load.php');

if (isset($_SESSION['user_id'])) {
    if (!$session->logout()) {
        redirect("index.php");
    }
} else {
    // If user_id is not set in session, handle the error or redirect
    redirect("index.php");
}
?>
