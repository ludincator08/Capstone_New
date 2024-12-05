<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <?php require('inc/links.php'); ?>
  <title><?= $general_r['site_title'] ?> - INCLUSIONS</title>

  
</head>

<body class="bg-light">
  <!-- Header -->
  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">OUR INCLUSIONS</h2>
    <!-- <hr> -->
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
      Lorem ipsum dolor sit amet consectetur adipisicing elit.
      Excepturi culpa officiis <br> aperiam eaque vitae non
      temporibus omnis quasi dolores odit.
    </p>
  </div>

  <div class="container">
    <div class="row">
      <?php

        $res = selectAll('inclusions');
        $path = INCLUSIONS_IMG_PATH;

        while($row = mysqli_fetch_assoc($res)){
          echo <<< data
            <div class="col-lg-4 col-md-6 mb-5 px-4">
              <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                <div class="d-flex align-items-center mb-2">
                  <img src="$path$row[icon]" width="40px" alt="">
                  <h5 class="m-0 ms-3">$row[name]</h5>
                </div>
                <p>
                  $row[description]
                </p>
              </div>
            </div> 
          data;
        }

      ?>
    </div>
  </div>

  <!-- Footer -->
  <?php require('inc/footer.php'); ?>

</body>

</html>