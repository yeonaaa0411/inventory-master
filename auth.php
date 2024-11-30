<?php include_once('includes/load.php'); ?>

<?php
// Validate required fields
$req_fields = array('username', 'password');
validate_fields($req_fields);

// Sanitize inputs
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if(empty($errors)) {
    // Call the authenticate function to get user data
    $user = authenticate($username, $password);

    if($user) {
        // If authentication is successful, create session with user ID
        $session->login($user['id']);

        // Update the user's last login time
        updateLastLogIn($user['id']);

        // Redirect user based on their user level
        if($user['user_level'] === '1') {
            // Admin redirect
            $session->msg("s", "Hello ".$user['username'].", Welcome to Inventory.");
            redirect('admin.php', false);
        } elseif ($user['user_level'] === '2') {
            // Order manager redirect
            $session->msg("s", "Hello ".$user['username'].", Welcome to Inventory.");
            redirect('add_order.php', false);
        } else {
            // Regular user redirect
            $session->msg("s", "Hello ".$user['username'].", Welcome to Inventory.");
            redirect('home.php', false);
        }

    } else {
        // Authentication failed
        $session->msg("d", "Sorry, Username/Password incorrect.");
        redirect('index.php', false);
    }

} else {
    // Validation errors
    $session->msg("d", $errors);
    redirect('login_v2.php', false);
}
?>
