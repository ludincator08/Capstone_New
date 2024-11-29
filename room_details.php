<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <?php require('inc/links.php'); ?>
  
  <title><?= $general_r['site_title'] ?> - FACILITIES DETAILS</title>

</head>

<body class="bg-light">
  <!-- Header -->
  <?php require('inc/header.php'); 
  
  if(!isset($_GET['id'])){
    redirect('rooms.php');
  }

  $data = filteration($_GET);

  $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'],1,0], 'iii');

  if(mysqli_num_rows($room_res) == 0)
  {
    redirect('rooms.php');
  }

  $room_data = mysqli_fetch_assoc($room_res);
  
  
  ?>

<div class="container">
  <div class="row">
      <!-- Bread crumbs -->
    <div class="col-12 my-5 px-4 mb-4">
      <h2 class="fw-bold"><?= $room_data['name'] ?></h2>
      <div style="font-size:14px;">
        <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
        <span class="text-secondary"> > </span>
        <a href="rooms.php" class="text-secondary text-decoration-none">FACILITIES</a>
        <span class="text-secondary"> > </span>
        <a href="#" class="text-secondary text-decoration-none"><?= $room_data['name'] ?></a>
      </div>
    </div>

    <div class="col-lg-7 col-md-17 px-4">
      <div id="room_Carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
            $room_img = ROOMS_IMG_PATH.'thumbnail.jpg';
            $img_q = mysqli_query($con, "SELECT * FROM `room_images` 
            WHERE `room_id` = '$room_data[id]'");

            if(mysqli_num_rows($img_q) > 0)
            {
              $active_class = 'active'; 

              while($img_res = mysqli_fetch_assoc($img_q)){
                echo "
                  <div class='carousel-item $active_class'>
                    <img src='".ROOMS_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                  </div>
                ";
                $active_class = '';
              }
            }
            else{
              echo "<div class='carousel-item active'>
                <img src='$room_img' class='d-block w-100' alt='thumbnail'>
              </div>";
            }
          ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#room_Carousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#room_Carousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>

    <div class="col-lg-5 col-md-12 px-4">
      <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <?php 
            echo <<< price
              <h4>₱$room_data[price]</h4>
            price;

            echo <<< rating
              <div class="mb-3">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </div>
            rating;

            $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
              INNER JOIN `room_features`rfea ON f.id = rfea.features_id 
              WHERE rfea.room_id = '$room_data[id]'");
              
            $features_data = ""; 
            while($fea_row = mysqli_fetch_assoc($fea_q)){
              $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $fea_row[name]
                </span>";
            }

            echo <<< features
              <div class="mb-3">
                <h5 class="mb-1">Features</h5>
                $features_data
              </div>
            features;

            $fac_q = mysqli_query($con, "SELECT facility.name FROM `facilities` facility
            INNER JOIN `room_facilities` r_facility ON facility.id = r_facility.facilities_id 
            WHERE r_facility.room_id = $room_data[id]");

            $facilities_data = "";
            while($fac_res = mysqli_fetch_assoc($fac_q)){
              $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                $fac_res[name]
              </span>";
            }
            echo <<< facilities
              <div class="mb-3">
                <h5 class="mb-1">Facilities</h5>
                $facilities_data
              </div>
            facilities;

            echo <<< guests
              <div class="mb-3">
                <h6 class="mb-1">Guests</h6>
                <span
                  class="badge rounded-pill bg-light text-dark text-wrap">
                  $room_data[adult] Adults
                </span>
                <span
                  class="badge rounded-pill bg-light text-dark text-wrap">
                  $room_data[children] Children
                </span>
            </div>
            guests;

            echo <<< area
              <div class="mb-3">
                <h5 class="mb-1">Area</h5>
                <span class="badge rounded-pill bg-light text-dark text-wrap mb-1 me-1">
                  $room_data[area] m<sup>2</sup>
                </span>
              </div>
            area;

            if(!$general_r['shutdown']){
              $login = 0; 
              if(isset($_SESSION['login']) && $_SESSION['login']){
                $login = 1;
              }
              echo <<< book
                <a onclick='checkLoginToReserve($login, $room_data[id])' class='btn w-100 text-white custom-bg shadow-none mb-1'>Reserve Now</a>
              book;
            }

            

          ?>
        </div>
      </div>
    </div>

    <div class="col-12 mt-4 px-4 ">
      <div class="mb-5">
        <h5>Description</h5>
        <p>
          <?= $room_data['description']; ?>
        </p>
      </div>
      <div>
        <h5 class="mb-3">Reviews & Ratings</h5>
        <div>
          <div class="d-flex align-items-center mb-2">
            <img src="images/facilities/IMG_41622.svg" width="30px">
            <h6 class="m-0 ms-2">Random user1</h6>
          </div>
          <p>
            Lorem ipsum dolor sit amet consectetur, adipisicing elit.
            Quas nihil tenetur rem. Tenetur itaque dignissimos reprehenderit
            sequi quod natus et?
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
      </div>
    </div>

    

  </div>
</div>

  <!-- Footer -->
  <?php require('inc/footer.php'); ?>
  <!-- <script>
    window.onbeforeunload = () => true;
  </script> -->

</body>

</html>