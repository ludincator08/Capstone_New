<?php 

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  date_default_timezone_set('Asia/Manila');
  
  session_start();
  if(!(isset($_SESSION['login'])) && $_SESSION['login'] == true){
    redirect('index.php');
  }

  if(isset($_POST['pay_now'])){
    
  }


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Now</title>
  </head>
<body>
  <div class="alert alert-success" role="alert">
    A simple success alert—check it out!
  </div>
</body>
</html>