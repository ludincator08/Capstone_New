<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <?php require('inc/links.php'); ?>
  <title><?= $general_r['site_title'] ?> - FACILITIES</title>

</head>

<body class="bg-light">
  <!-- Header -->
  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
    <!-- <hr> -->
    <div class="h-line bg-dark"></div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <!-- Filters -->
      <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
          <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2">FILTERS</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
              <!-- Check Availability -->
              <div class="border bg-light p-3 rounded mb-3">
                <h5 class="mb-3" style="font-size: 18px;">CHECK AVAILABILITY</h5>
                <label class="form-label">Check-in</label>
                <input type="date" class="form-control shadow-none mb-3" />
                <label class="form-label">Check-out</label>
                <input type="date" class="form-control shadow-none" />
              </div>
              <!-- Facilities -->
              <div class="border bg-light p-3 rounded mb-3">
                <h5 class="mb-3" style="font-size: 18px;">FACILITIES</h5>
                <div class="mb-2">
                  <input type="checkbox" id="f1" class="form-check-input shadow-none me-1" />
                  <label class="form-check-label" for="f1">Facility One</label>
                </div>
                <div class="mb-2">
                  <input type="checkbox" id="f2" class="form-check-input shadow-none me-1" />
                  <label class="form-check-label" for="f2">Facility Two</label>
                </div>
                <div class="mb-2">
                  <input type="checkbox" id="f3" class="form-check-input shadow-none me-1" />
                  <label class="form-check-label" for="f3">Facility Three</label>
                </div>
              </div>
              <!-- GUESTS -->
              <div class="border bg-light p-3 rounded mb-3">
                <h5 class="mb-3" style="font-size: 18px;">GUESTS</h5>
                <div class="d-flex">
                  <div class="me-3">
                    <label class="form-label">Adults</label>
                    <input type="number" class="form-control shadow-none">
                  </div>
                  <div>
                    <label class="form-label">Children</label>
                    <input type="number" class="form-control shadow-none">
                  </div>
                </div>
              </div>

            </div>
        </nav>
      </div>
      <!-- Cards -->
      <div class="col-lg-9 col-md-12 px-4">

        <?php 
          $facility_res = select("SELECT * FROM `facilities` WHERE `status`=? AND `removed`=?", [1, 0], 'ii');

          while($facility_data = mysqli_fetch_assoc($facility_res))
          {
            
            // get inclusions of facilities
            $fac_q = mysqli_query($con, "SELECT inclusion.name FROM `inclusions` inclusion
            INNER JOIN `facilities_inclusions` r_inclusion ON inclusion.id = r_inclusion.inclusions_id 
            WHERE r_inclusion.facility_id = $facility_data[id]");

            $facilities_data = "";
            while($fac_row = mysqli_fetch_assoc($fac_q)){
              $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                $fac_row[name]
              </span>";
            }

            // get thumbnail of image
            $facilities_thumbnail = FACILITIES_IMG_PATH.'thumbnail.jpg';
            $facilities_q = mysqli_query($con, "SELECT * FROM `facility_images` 
            WHERE `facility_id` = '$facility_data[id]'
            AND `thumb` = '1'");

            if(mysqli_num_rows($facilities_q) > 0){
              $facilities_row = mysqli_fetch_assoc($facilities_q);
              $facilities_thumbnail = FACILITIES_IMG_PATH.$facilities_row['image'];
            }

            // Print Facility Card

            $reserve_btn = "";

            if(!$general_r['shutdown']){
              $login = 0;
              if(isset($_SESSION['login']) && $_SESSION['login'] == true){
                $login = 1;
              }
              $reserve_btn = "<button onclick='checkLoginToReserve($login, $facility_data[id])' class='btn w-100 btn-sm text-white custom-bg shadow-none mb-2'>Reserve Now</button>";
            }

            echo <<< data
              <div class="card mb-4 border-0 shadow">
                <div class="row g-0 p-3 align-items-center">

                  <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                    <img src="$facilities_thumbnail" alt="Thumbnail" class="img-fluid rounded" >
                  </div>
                  <div class="col-md-5 px-lg-3 px-md-3 px-0">
                    <h5 class="mb-4">$facility_data[name]</h5>
                    <div class="facilities mb-3">
                      <h6 class="mb-1">Inclusions</h6>
                      $facilities_data 
                    </div>
                    <div class="desc ">
                      <div class="mb-1">
                        <h6>Description</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap text-start' style='font-weight: 450;'>
                          $facility_data[description]
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
                    <h6 class="mb-4">₱$facility_data[price] per hour</h6>
                    $reserve_btn
                    <a href="facilities_detail.php?id=$facility_data[id]" class="btn w-100 btn-sm btn-outline-dark shadow-none">More Details</a>
                  </div>
                </div>
              </div>
            data;
            // style="width: 320px; height: 180px; object-fit: cover;"

          }
        ?>

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