<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <?php require('inc/links.php'); ?>
  
  <title><?= $general_r['site_title'] ?> - CONFIRM RESERVATION</title>

</head>

<body class="bg-light">
  <!-- Header -->
  <?php require('inc/header.php'); 

  /*
  Check facilities id from url is present or not
  Shutdown  mode  is active or not 
  User logged in or not
  */
  
  if(!isset($_GET['id']) || $general_r['shutdown'] == true){
    redirect('facilities.php');
  }
  else if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
    redirect('index.php');
  }

  // filter and get reserve data 
  $data = filteration($_GET);

  $facilities_res = select("SELECT * FROM `facilities` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'],1,0], 'iii');

  if(mysqli_num_rows($facilities_res) == 0)
  {
    redirect('facilities.php');
  }

  $facilities_data = mysqli_fetch_assoc($facilities_res);

  $_SESSION['facilities'] = [
    "id" => $facilities_data['id'],
    "name" => $facilities_data['name'],
    "price" => $facilities_data['price'],
    "payment" => null,
    "available" => false,
  ];

  $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");
  $user_data = mysqli_fetch_assoc($user_res);
  
  
  ?>

<div class="container">
  <div class="row">
      <!-- Bread crumbs -->
    <div class="col-12 my-5 px-4 mb-4">
      <h2 class="fw-bold">CONFIRM RESERVATION</h2>
      <div style="font-size:14px;">
        <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
        <span class="text-secondary"> > </span>
        <a href="facilities.php" class="text-secondary text-decoration-none">FACILITIES</a>
        <span class="text-secondary"> > </span>
        <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
      </div>
    </div>

    <div class="col-lg-7 col-md-12 px-4">
      <?php 
        $facilities_thumb = FACILITIES_IMG_PATH.'thumbnail.jpg';
        $facilities_q = mysqli_query($con, "SELECT * FROM `facility_images` 
          WHERE `facility_id` = '$facilities_data[id]'
          AND `thumb` = '1'");

        if(mysqli_num_rows($facilities_q) > 0){
          $facilities_row = mysqli_fetch_assoc($facilities_q);
          $facilities_thumb = FACILITIES_IMG_PATH.$facilities_row['image'];
        }

        echo <<< data
          <div class="card p-3  shadow-sm rounded">
            <img src="$facilities_thumb" class="img-fluid rounded mb-3">
            <h5>$facilities_data[name]</h5>
            <h6>₱$facilities_data[price] per hour</h6>
          </div>
        data;

      ?>
    </div>

    <div class="col-lg-5 col-md-12 px-4">
      <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <form action="pay_now.php" id="reservation_form">
            <h6 class="mb-3">RESERVATION DETAILS</h6>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label mb-1">Name</label>
                <input type="text" name="name" value="<?=$user_data['name'] ?>" class="form-control shadow-none" disabled>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="number" name="phonenum" value="<?=$user_data['phonenum'] ?>" class="form-control shadow-none" disabled>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control shadow-none" rows="1" disabled><?= $user_data['address'] ?></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Check-in</label>
                <input type="datetime-local" onchange="check_availability()" name="checkin" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Check-out</label>
                <input type="datetime-local" onchange="check_availability()" name="checkout" class="form-control shadow-none" required>
              </div>
              <div class="col-12">
                <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                  <span class="visually-hidden">Loading....</span>
                </div>
                <h6 class="mb-3 text-danger" id="pay_info">Provide check-in & check-out date!</h6>
                <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

  <!-- Footer -->
  <?php require('inc/footer.php'); ?>
  <!-- <script>
    window.onbeforeunload = () => true; //alert message 
  </script> -->

  <script>
    let reservation_form = document.getElementById('reservation_form');
    let info_loader = document.getElementById('info_loader');
    let pay_info = document.getElementById('pay_info');

    

    function check_availability()
    {
      let checkin_val = reservation_form.elements['checkin'].value;
      let checkout_val = reservation_form.elements['checkout'].value;

      reservation_form.elements['pay_now'].setAttribute('disabled', true);

      if(checkin_val != '' && checkout_val != '')
      {
        pay_info.classList.add('d-none');
        pay_info.classList.replace('text-dark', 'text-danger');
        info_loader.classList.remove('d-none');
        
        let data = new FormData();
        
        data.append('check_availability', '');
        data.append('check_in', checkin_val);
        data.append('check_out', checkout_val);
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/confirm_reservation.php", true);
        
        xhr.onload = function(){ 
          let data = JSON.parse(this.responseText);
          
          if(data.status == 'check_in_out_equal'){
            pay_info.innerText = "You cannot check-out for the same Hours!";
          }
          else if(data.status == 'check_out_earlier'){
            pay_info.innerText = "Check-out date is earlier than check-in date!";
          }
          else if(data.status == 'check_in_earlier'){
            pay_info.innerText = "Check-in date is earlier than today's date!";
          }
          else if(data.status == 'unavailable'){
            pay_info.innerText = "Facilities are not available for this Check-in date!";
          }
          else{
            pay_info.innerText = "No. of Hours: "+data.hours+ "\nTotal Amount to Pay: ₱" + data.payment;
            pay_info.classList.replace('text-danger', 'text-dark');
            reservation_form.elements['pay_now'].removeAttribute('disabled');
          }
          pay_info.classList.remove('d-none');
          info_loader.classList.add('d-none');
        }

        xhr.send(data);
      }
    }




  </script>

</body>

</html>