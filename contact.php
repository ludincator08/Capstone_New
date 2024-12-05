<?php
session_start(); // Start the session for generating and validating tokens

// Include the necessary files
require('admin/inc/db_config.php'); // This will give us access to filteration() and database functions
require('admin/inc/essentials.php'); // Assuming it contains the alert function

// Generate a new token if it doesn't exist
if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

// Fetch user data if logged in (optional - only fill name and email for logged-in users)
if (isset($_SESSION['uId'])) {
    // Fetch user data
    $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");
    $user_data = mysqli_fetch_assoc($user_res);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    // Validate the token
    if (!isset($_SESSION['form_token']) || $_SESSION['form_token'] !== $_POST['form_token']) {
        $_SESSION['alert'] = ['error', 'Invalid submission. Please try again.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Invalidate the token to prevent resubmission
    unset($_SESSION['form_token']);

    // Process the form data using the filteration() function from db_config.php
    $frm_data = filteration($_POST);

    // Insert data into the database
    $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
    $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];
    $res = insert($q, $values, 'ssss'); // Assuming the insert function works properly

    // Handle the response
    if ($res == 1) {
        $_SESSION['alert'] = ['success', 'Your message has been sent successfully.'];
    } else {
        $_SESSION['alert'] = ['error', 'Server down! Try again later.'];
    }

    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?php require('inc/links.php'); ?>
    <title><?= $general_r['site_title']?> - CONTACT</title>
</head>

<body class="bg-light">
    <!-- Header -->
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Contact Us</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Excepturi culpa officiis <br> aperiam eaque vitae non
            temporibus omnis quasi dolores odit.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <!-- Contact Details Section -->
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="<?= $contact_r['iframe'] ?>" loading="lazy"></iframe>
                    <h5>Address</h5>
                    <a href="<?= $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2 pop">
                        <i class="bi bi-geo-alt-fill"></i> <?= $contact_r['address'] ?>
                    </a>
                    <h5 class="mt-4">Call us</h5>
                    <a href="tel: +<?= $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark pop">
                        <i class="bi bi-telephone-fill"></i> +<?= $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php 
                    if($contact_r['pn2'] != 0){
                        echo <<<data
                        <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark pop">
                            <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                        </a>
                        data;
                    }
                    ?>
                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?= $contact_r['email'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark pop">
                        <i class="bi bi-envelope-fill"></i> <?= $contact_r['email'] ?>
                    </a>
                    <h5 class="mt-4">Follow us</h5>
                    <?php
                    if($contact_r['tw'] != '') {
                        echo <<<data
                        <a href="$contact_r[tw]" target="_blank" class="d-inline-block mb-3 text-dark fs-5 me-2 pop">
                            <i class="bi bi-twitter me-1"></i>
                        </a>
                        data;
                    }
                    ?>
                    <a href="<?= $contact_r['fb'] ?>" target="_blank" class="d-inline-block mb-3 text-dark fs-5 me-2 pop">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="<?= $contact_r['insta'] ?>" target="_blank" class="d-inline-block text-dark fs-5 pop">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>

            <!-- Contact Form Section -->
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Send a message</h5>
                        <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input type="text" name="name" value="<?= isset($user_data) ? $user_data['name'] : '' ?>" 
                            <?= isset($user_data) ? 'disabled' : '' ?> required class="form-control shadow-none" />
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input type="text" name="email" value="<?= isset($user_data) ? $user_data['email'] : '' ?>" 
                            <?= isset($user_data) ? 'disabled' : '' ?> required class="form-control shadow-none" />
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input type="text" name="subject" required class="form-control shadow-none" />
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Messages</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3 pop">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    window.onload = function() {
    <?php if (isset($_SESSION['alert'])): ?>
        alert('<?= $_SESSION['alert'][0] ?>', '<?= $_SESSION['alert'][1] ?>');
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
};
</script>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>
</body>

</html>
