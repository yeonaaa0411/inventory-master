<?php
// Includes necessary files for session, validation, and loading configuration
include_once('includes/load.php');

$req_fields = array('username','password');  // Set the required fields
validate_fields($req_fields);  // Validate the input fields

$username = remove_junk($_POST['username']); // Clean user inputs
$password = remove_junk($_POST['password']);

if (empty($errors)) {
    // Attempt to authenticate user
    $user = authenticate_v2($username, $password);

    if ($user) {
        // Successful authentication
        $session->login($user['id']);  // Set session with user id
        updateLastLogIn($user['id']);  // Update last login time

        // Check user level and redirect accordingly
        if ($user['user_level'] === '1') {
            $session->msg("s", "Hello ".$user['username'].", Welcome to Inventory.");
            redirect('admin.php', false);  // Admin dashboard
        } elseif ($user['user_level'] === '2') {
            $session->msg("s", "Hello ".$user['username'].", Welcome to Inventory.");
            redirect('add_order.php', false);  // Order management page
        } else {
            $session->msg("s", "Hello ".$user['username'].", Welcome to Inventory.");
            redirect('home.php', false);  // Home page for other users
        }
    } else {
        // Authentication failed
        $session->msg("d", "Sorry Username/Password incorrect.");
        redirect('index.php', false);  // Go back to the login page
    }
} else {
    // Validation failed
    $session->msg("d", $errors);
    redirect('login_v2.php', false);  // Return to the login page
}
?>
