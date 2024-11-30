<?php
ob_start();
require_once('includes/load.php');
if($session->isUserLoggedIn(true)) { redirect('admin.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h1>Welcome</h1>
        <p>Sign in to start your session</p>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="auth_v2.php" class="login-form">
        <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info">Login</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
