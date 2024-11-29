<?php 

  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');

  date_default_timezone_set('Asia/Manila');

  if(isset($_POST['check_availability']))
  {
    $frm_data = filteration($_POST);
    $status  = "";
    $result = "";

    // check in and out validations

    $today_date = new DateTime(date('Y-m-d H:00'));
    $checkin_date = new DateTime($frm_data['check_in']);
    $checkout_date = new DateTime($frm_data['check_out']);

    $checkin_date->setTime($checkin_date->format('H'), 0, 0);  // Set minutes and seconds to 0
    $checkout_date->setTime($checkout_date->format('H'), 0, 0); // Set minutes and seconds to 0

    if($checkin_date == $checkout_date){
      $status = 'check_in_out_equal';
      $result = json_encode(["status" => $status]);
    }
    else if($checkout_date < $checkin_date){
      $status = 'check_out_earlier';
      $result = json_encode(["status" => $status]);
    }
    else if($checkin_date < $today_date){
      $status = 'check_in_earlier';
      $result = json_encode(["status" => $status]);
    }
    
    // Check reservation availability if status if blank else return error 
    if($status != ''){
      echo $result;
    }
    else {
      session_start();
      $_SESSION['room'];

      // check room is available or not
      // Calculate difference in hours instead of days
      $interval = $checkin_date->diff($checkout_date);
      $hours_diff = ($interval->days * 24) + $interval->h;  // Get total hours

      $payment = $_SESSION['room']['price'] * $hours_diff;  // Calculate payment based on hours

      $_SESSION['room']['payment'] = $payment;
      $_SESSION['room']['available'] = true;

      $result = json_encode(["status"=>'available', "hours"=>$hours_diff, "payment"=>$payment]);
      echo $result;
    }
  }


?>