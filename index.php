<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <?php require('inc/links.php'); ?>
  <title><?= $general_r['site_title'] ?> - Homes</title>

  <style>
    .availability-form {
      margin-top: -50px;
      z-index: 2;
      position: relative;
    }

    @media screen and (max-width: 575px) {
      .availability-form {
        margin-top: 25px;
        padding: 0 35px;
      }
    }
  </style>

</head>

<body class="bg-light">
  <!-- Header -->
  <?php require('inc/header.php'); ?>

  <!-- Swiper Carousel-->
  <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <?php
          $res = selectAll('carousel');
          while($row = mysqli_fetch_assoc($res)){
            $path = CAROUSEL_IMG_PATH;
            echo <<< data
              <div class="swiper-slide">
                <img src="$path$row[image]"  style="width: 100%; height: 417px; object-fit: cover;" class="w-100 d-block" />
              </div>
            data;
            // style="height: 240px; object-fit: cover;" //add that in img to adjust that height of the image
          }
        ?>
      </div>
    </div>
  </div>

  <!-- Check availability form  -->
  <div class="container availability-form">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded mb-5">
        <h5 class="mb-4">Check Reservation Availability</h5>
        <form>
          <div class="row align-items-end">
            <div class="col-lg-4 mb-3">
              <label class="form-label" style="font-weight: 500">FACILITY</label>
              <!--<input type="date" class="form-control shadow-none" /> -->
              <select class="form-select shadow-none">
                <option value="1">BASKETBALL COURT</option>
                <option value="2">CLUB HOUSE</option>
                <option value="3">SWIMMING POOL</option>
              </select>
            </div>
            <div class="col-lg-4 mb-3">
              <label class="form-label" style="font-weight: 500">DATE</label>
              <input type="date" class="form-control shadow-none" />
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500">TIME</label>
              <select class="form-select shadow-none">
                <option value="1">08:00-10:00</option>
                <option value="2">10:00-12:00</option>
                <option value="3">12:00-02:00</option>
              </select>
            </div>
            <!--<div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 500">Children</label>
              <select class="form-select shadow-none">
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div>-->
            <div class="col-lg-1 mb-lg-3 mt-2">
              <button
                type="submit"
                class="btn text-white shadow-none custom-bg pop">
                Submit
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-4 mb-4 order-lg-1 order-md-1 order-1">
        <img src="images/about/about.jpg" alt="" class="w-100">
      </div>
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h1 class="mb-3">Lorem ipsum dolor sit.</h1>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          Voluptate error aut, hic facere iste sequi vero?
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
          Voluptate error aut, hic facere iste sequi vero?
        </p>
        <div class="col-lg-12 mt-5">
          <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none pop">Read More >>></a>
        </div>
      </div>
      <!-- Img -->
    </div>
  </div>

  <!-- OUR FACILITIESS -->
  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR FACILITIES</h2>

  <div class="container">
    <div class="row">

    <?php 
      $facility_res = select("SELECT * FROM `facilities` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3", [1, 0], 'ii');

      while($facility_data = mysqli_fetch_assoc($facility_res))
      {
        // get inclusions of facility
        $fac_q = mysqli_query($con, "SELECT inclusion.name FROM `inclusions` inclusion
        INNER JOIN `facilities_inclusions` r_inclusion ON inclusion.id = r_inclusion.inclusions_id 
        WHERE r_inclusion.facility_id = $facility_data[id]");

        $inclusions_data = "";
        while($fac_row = mysqli_fetch_assoc($fac_q)){
          $inclusions_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
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

        $reserve_btn = "";

        if(!$general_r['shutdown']){
          $login = 0;
          if(isset($_SESSION['login']) && $_SESSION['login'] == true){
            $login = 1;
          }
          $reserve_btn = "<button onclick='checkLoginToReserve($login, $facility_data[id])' class='btn btn-sm text-white custom-bg shadow-none pop'>Reserve Now</button>";
        }

        // Print Facility Card
        echo <<< data
          <div class="col-lg-4 col-md-6 my-3 pop">
            <div class="card border-0 shadow" style="max-width: 350px; margin: auto">
              <img src="$facilities_thumbnail" style="height: 197px; object-fit: cover;" class="card-img-top" alt="... " />
              <div class="card-body">
                <h5>$facility_data[name]</h5>
                <h6 class="mb-4">₱$facility_data[price] per hour</h6>
                <div class="Inclusions mb-4">
                  <h6 class="mb-1">Inclusions</h6>
                  $inclusions_data
                </div>
                <div class="Description mb-4">
                  <h6 class="mb-1">Description</h6>
                  <span class='badge rounded-pill bg-light text-dark text-wrap text-start' style="font-weight: 450;">
                    $facility_data[description]
                  </span>
                </div>
                <div class="rating mb-4">
                  <h6 class="mb-1">Rating</h6>
                  <span class="badge rounded-pill bg-light">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                  </span>
                </div>
                <div class="d-flex justify-content-evenly mb-2">
                  $reserve_btn
                  <a href="facilities_detail.php?id=$facility_data[id]" class="btn btn-sm btn-outline-dark shadow-none pop">More Details</a>
                </div>
              </div>
            </div>
          </div>

        data;
        // style="width: 320px; height: 180px; object-fit: cover;"

      }
      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none pop">More Facilities >>></a>
      </div>
    </div>
  </div>

  <!-- OUR Inclusions -->
  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR INCLUSIONS</h2>

  <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
      <?php

        $res = mysqli_query($con, "SELECT * FROM `inclusions` ORDER BY `id` DESC LIMIT 5");
        $path = INCLUSIONS_IMG_PATH;

        while($row = mysqli_fetch_assoc($res))
        {
          echo <<< data
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3 pop">
              <img src="$path$row[icon]" width="60px">
              <h5 class="mt-3">$row[name]</h5>
            </div>
          data;
        }
      ?>
      <div class="col-lg-12 text-center mt-5">
        <a href="inclusions.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none pop">More Inclusions >>></a>
      </div>
    </div>
  </div>

  <!-- Testimonials -->
  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>

  <div class="container mt-5">
    <!-- Swiper for Testimonials -->
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">

        <div class="swiper-slide bg-white p-4">
          <div class="profile d-flex align-items-center mb-3">
            <img src="images/users/IMG_44030.jpeg" alt="Testimonials" width="30px">
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
        <div class="swiper-slide bg-white p-4">
          <div class="profile d-flex align-items-center mb-3">
            <img src="images/users/IMG_44030.jpeg" alt="Testimonials" width="30px">
            <h6 class="m-0 ms-2">Random user2</h6>
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
        <div class="swiper-slide bg-white p-4">
          <div class="profile d-flex align-items-center mb-3">
            <img src="images/users/IMG_44030.jpeg" alt="Testimonials" width="30px">
            <h6 class="m-0 ms-2">Random user3</h6>
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
        <div class="swiper-slide bg-white p-4">
          <div class="profile d-flex align-items-center mb-3">
            <img src="images/users/IMG_44030.jpeg" alt="Testimonials" width="30px">
            <h6 class="m-0 ms-2">Random user4</h6>
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
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none pop">More Testimonials >>></a>
    </div>
  </div>

  <!-- Reach Us -->
  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>

  <div class="container">
    <div class="row shadow-sm">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" height="320px" src="<?= $contact_r['iframe']; ?>" loading="lazy"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5 class="">Call us</h5>
          <a href="tel: +<?= $contact_r['pn1']; ?>" class="d-inline-block mb-2 text-decoration-none text-dark pop">
            <i class="bi bi-telephone-fill"></i> +<?= $contact_r['pn1']; ?>
          </a>
          <br>
          <?php
            if($contact_r['pn2'] != 0){
              echo<<<data
                <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark pop">
                  <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                </a>
              data;
            }
          ?>
        </div>
        <div class="bg-white p-4 rounded mb-4">
          <h5>Fallow us</h5>
          <?php 
            if($contact_r['tw'] != ''){
              echo<<<data
              <a href="$contact_r[tw]" target="_blank" class="d-inline-block mb-3 pop">
                <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-twitter me-1"></i>Twitter</span>
              </a>
              <br>
              data;
            }
          ?>
          
          <a href="<?= $contact_r['fb'] ?>" target="_blank" class="d-inline-block mb-1 pop">
            <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-facebook me-1"></i>Facebook</span>
          </a>
          <br>
          <a href="<?= $contact_r['insta'] ?>" target="_blank" class="d-inline-block pop">
            <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-instagram me-1"></i>Instagram</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Password reset Modal and code -->
  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form id="recovery-form">
                  <div class="modal-header">
                      <h5 class="modal-title d-flex align-items-center">
                          <i class="bi bi-shield-lock fs-3 me-2"></i> Set up New Password
                      </h5>
                  </div>
                  <div class="modal-body">
                      <div class="mb-4">
                          <label class="form-label">New Password </label>
                          <input type="password" name="pass" class="form-control shadow-none" required />
                          <label class="form-label">Confirm Password </label>
                          <input type="password" name="confirm_pass" class="form-control shadow-none" required />
                          <input type="hidden" name="email">
                          <input type="hidden" name="token">
                      </div>
                      <div
                          class="mb-2 text-end">
                          <button type="button" class="btn shadow-none me-2 pop" data-bs-dismiss="modal">
                              CANCEL
                          </button>
                          <button type="submit" class="btn btn-dark shadow-none pop"> SUBMIT </button>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <!-- Footer -->
  <?php require('inc/footer.php'); ?>

  <?php
  
    if(isset($_GET['account_recovery']))
    {
      $data = filteration($_GET);

      $t_date = date("Y-m-d");

      $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
        [$data['email'], $data['token'], $t_date], "sss");

      if(mysqli_num_rows($query) == 1)
      {
        echo <<< showModal
        <script>
          var myModal = document.getElementById('recoveryModal');
          
          myModal.querySelector("input[name='email']").value = '$data[email]';
          myModal.querySelector("input[name='token']").value = '$data[token]';
          
          var modal = bootstrap.Modal.getOrCreateInstance(myModal);
          modal.show();
        </script>
        showModal;
      }
      else{
        alert('error', 'Invalid or Expired Link');
      }
    }
  
  ?>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
    });

    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      loop: true,
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        }
      }
    });

    // Recover account
    let recovery_form = document.getElementById('recovery-form');
    
    recovery_form.addEventListener('submit', (e) => {
      e.preventDefault();
      
      let data = new FormData();
      
      data.append('email', recovery_form.elements['email'].value);
      data.append('token', recovery_form.elements['token'].value);
      data.append('pass', recovery_form.elements['pass'].value);
      data.append('confirm_pass', recovery_form.elements['confirm_pass'].value);
      data.append('recover_user', '');
      
      var myModal = document.getElementById('recoveryModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.show();
      
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/login_register.php", true);
      
      xhr.onload = function(){ 
          if(this.responseText == 'failed'){
              alert('error', 'Account reset failed!');
          }
          else if(this.responseText == 'pass_mismatch'){
                alert('error', 'Password Mismatch!');
          }
          else{
              alert('success', 'Account Reset successful!');
              recovery_form.reset();
              modal.hide();
          }
      }
      xhr.send(data);
    });

  </script>
</body>

</html>