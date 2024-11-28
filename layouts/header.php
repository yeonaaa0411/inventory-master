<?php
ob_start(); // Start output buffering at the top of your PHP files to avoid output issues
require_once('includes/load.php');
$user = current_user();  // Ensure user is fetched after session start
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title)) echo remove_junk($page_title); elseif (!empty($user)) echo ucfirst($user['name']); else echo "Simple Inventory System";?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="./libs/css/main.css" />
    <style>

  /* Positioning the profile icon in the header */
  #header .profile {
    position: absolute;
    right: 20px; /* Adjust the distance from the right edge */
    top: 50%;
    transform: translateY(-50%); /* Vertically center the profile icon */
    display: flex;
    align-items: center;
  }

  /* Styling the profile image */
  #header .profile img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid #007BFF; /* Adding border around the profile picture */
    margin-right: 10px; /* Space between the image and name */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  /* Hover effect for the profile image */
  #header .profile img:hover {
    transform: scale(1.1); /* Slight zoom on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add subtle shadow */
  }

  /* Styling the profile name link */
  #header .profile a {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  /* Hover effect for the profile name */
  #header .profile a:hover {
    color: #007BFF; /* Highlight name on hover */
  }

  /* Dropdown menu styling */
  #header .profile .dropdown-menu {
    min-width: 200px;
    font-size: 16px;
  }

  /* Dropdown menu items styling */
  #header .profile .dropdown-menu li a {
    padding: 10px 15px;
    color: #555;
  }

  /* Hover effect for dropdown items */
  #header .profile .dropdown-menu li a:hover {
    background-color: #f8f9fa;
    color: #007BFF;
  }
</style>

  </head>
  <body>
    <?php if ($session->isUserLoggedIn(true)): ?>
      <header id="header">
        <div class="logo pull-left">
          <img src="includes/uploads/BPSRLogo.png" alt="BPSR Logo" style="height: 100%; width: 100%;">
        </div>
        <div class="header-content">
          <div class="header-date pull-left">
            <strong><?php echo date("F j, Y, g:i a");?></strong>
          </div>
          <div class="pull-right clearfix">
            <ul class="info-menu list-inline list-unstyled">
              <li class="profile">
                <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                  <img src="./uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
                  <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="./profile.php?id=<?php echo (int)$user['id'];?>">
                      <i class="glyphicon glyphicon-user"></i> Profile
                    </a>
                  </li>
                  <li class="last">
                    <a href="./logout.php">
                      <i class="glyphicon glyphicon-off"></i> Logout
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </header>

      <div class="sidebar">
        <?php if($user['user_level'] === '1'): ?>
          <!-- Admin menu -->
          <?php include_once('admin_menu.php');?>
        <?php elseif($user['user_level'] === '2'): ?>
          <!-- Employee -->
          <?php include_once('employee_menu.php');?>
        <?php elseif($user['user_level'] === '3'): ?>
          <!-- User menu -->
          <?php include_once('user_menu.php');?>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="page">
      <div class="container-fluid">
