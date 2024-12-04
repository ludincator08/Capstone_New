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
    redirect('facilities.php');
  }

  $data = filteration($_GET);

  $facility_result = select("SELECT * FROM `facilities` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'],1,0], 'iii');

  if(mysqli_num_rows($facility_result) == 0)
  {
    redirect('facilities.php');
  }

  $facility_data = mysqli_fetch_assoc($facility_result);
  
  
  ?>

<div class="container">
  <div class="row">
      <!-- Bread crumbs -->
    <div class="col-12 my-5 px-4 mb-4">
      <h2 class="fw-bold"><?= $facility_data['name'] ?></h2>
      <div style="font-size:14px;">
        <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
        <span class="text-secondary"> > </span>
        <a href="facilities.php" class="text-secondary text-decoration-none">FACILITIES</a>
        <span class="text-secondary"> > </span>
        <a href="#" class="text-secondary text-decoration-none"><?= $facility_data['name'] ?></a>
      </div>
    </div>

    <div class="col-lg-7 col-md-17 px-4">
      <div id="facility_Carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
            $facility_img = FACILITIES_IMG_PATH.'thumbnail.jpg';
            $img_q = mysqli_query($con, "SELECT * FROM `facility_images` 
            WHERE `facility_id` = '$facility_data[id]'");

            if(mysqli_num_rows($img_q) > 0)
            {
              $active_class = 'active'; 

              while($img_res = mysqli_fetch_assoc($img_q)){
                echo "
                  <div class='carousel-item $active_class'>
                    <img src='".FACILITIES_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                  </div>
                ";
                $active_class = '';
              }
            }
            else{
              echo "<div class='carousel-item active'>
                <img src='$facility_img' class='d-block w-100' alt='thumbnail'>
              </div>";
            }
          ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#facility_Carousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#facility_Carousel" data-bs-slide="next">
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
              <h4>₱$facility_data[price]</h4>
            price;

            echo <<< rating
              <div class="mb-3">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </div>
            rating;

            $fac_q = mysqli_query($con, "SELECT facility.name FROM `facilities` facility
            INNER JOIN `facilities_inclusions` r_facility ON facility.id = r_facility.inclusions_id 
            WHERE r_facility.facility_id = $facility_data[id]");

            $facilities_data = "";
            while($fac_res = mysqli_fetch_assoc($fac_q)){
              $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                $fac_res[name]
              </span>";
            }
            echo <<< facilities
              <div class="mb-3">
                <h5 class="mb-1">Inclusions</h5>
                $facilities_data
              </div>
            facilities;

            echo <<< area
              <div class="mb-3">
                <h5 class="mb-1">Area</h5>
                <span class="badge rounded-pill bg-light text-dark text-wrap mb-1 me-1">
                  $facility_data[area] m<sup>2</sup>
                </span>
              </div>
            area;

            if(!$general_r['shutdown']){
              $login = 0; 
              if(isset($_SESSION['login']) && $_SESSION['login']){
                $login = 1;
              }
              echo <<< book
                <a onclick='checkLoginToReserve($login, $facility_data[id])' class='btn w-100 text-white custom-bg shadow-none mb-1'>Reserve Now</a>
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
          <?= $facility_data['description']; ?>
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