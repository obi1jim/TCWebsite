<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title ?? 'Unknown Page Title'?> | <?=APP_NAME?></title>
    <link rel="stylesheet" type="text/css" href="<?=ROOT?>/assets/css/bootstrap.min.css">
</head>
<body>
    <?php
        $session = new \Core\Session;
        $last_Activity = $session->get('last_activity');
        $current_time = time();

        if($session->is_logged_in()) {
          $current_page = $_SERVER['REQUEST_URI'];
          $current_page = explode('/', $current_page);
          $current_page = end($current_page);
          
          //echo "last_activity: " . $last_Activity . "<br>";
          //echo "current time: " . $current_time . "<br>";
          
          if($current_time - $last_Activity > 1800) { // 30 minutes
            echo "
            <script>
              alert('Your session has timed out. Please log in again.');
              window.location.href = '" . ROOT . "/logout';
            </script>
            ";
            $session->logout();
          } 
          else{
            $session->set('last_activity', $current_time);
          }     
          //this is to check if the user is on the login page and if they are,
          //then we will ask them if they want to log out or go back to the previous page
          if($current_page == 'login') {
            
            echo "
            <script>
                if (confirm('Do you want to log out?')) {
                    window.location.href = '" . ROOT . "/logout';
                } else {
                    window.history.back();
                }
            </script>
            ";
          }

        }
            
    ?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?=ROOT?>">Toke Commettee Website</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?=ROOT?>">Home</a>
        </li>
        <?php if($session->is_logged_in()): ?> <!--if the user is logged in, show the link to the dashboard-->
            <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?=ROOT?>/about">About</a> 
            </li>
            <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Hi, <?=$session->user('full_name')?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Change Password</a></li>
            <li><a class="dropdown-item" href="<?=ROOT?>/logout">Logout</a></li>
          </ul>
        </li>
        <?php else: ?><!--if the user is not logged in, show the login and signup links-->
            <li class="nav-item">
            <a class="nav-link" href="<?=ROOT?>/login">Login</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?=ROOT?>/signup">Signup</a>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
