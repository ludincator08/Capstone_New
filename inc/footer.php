<style>
    .pop:hover {
        border-top-color: var(--teal) !important;
        transform: scale(1.05);
        transition: all 0.3s;
    }
</style>
<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2"><?= $general_r['site_title']?></h3>
            <p><?= $general_r['site_about'] ?></p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none pop">Home</a> <br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none pop">Facilities</a> <br>
            <a href="inclusions.php" class="d-inline-block mb-2 text-dark text-decoration-none pop">Inclusions</a> <br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none pop">Contact Us</a> <br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none pop">About</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="">Fallow us</h5>
            <?php 
            if($contact_r['tw'] != ''){
                echo <<< data
                <a href="$contact_r[tw]" target="_blank" class="d-inline-block mb-2 text-dark text-decoration-none mb-2 pop">
                    <i class="bi bi-twitter me-1 "></i>Twitter
                </a>
                <br>
                data;
            }
            ?>
            <a href="<?= $contact_r['fb'] ?>" target="_blank" class="d-inline-block mb-2 text-dark text-decoration-none mb-2 pop">
                <i class="bi bi-facebook me-1 "></i>Facebook
            </a>
            <br>
            <a href="<?= $contact_r['insta'] ?>" target="_blank" class="d-inline-block mb-2 text-dark text-decoration-none pop">
                <i class="bi bi-instagram me-1 "></i>Instagram
            </a>
        </div>
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Designed and Developed by H.O.M.E.S</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    function alert(type, msg, position='body'){
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show " role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        if(position == 'body'){
            document.body.append(element);
            element.classList.add('custom-alert');
        }
        else{
            document.getElementById(position).appendChild(element);
        }
        setTimeout(remAlert, 3000);
    }

    function remAlert(){
        document.getElementsByClassName('alert')[0].remove();
    }
    
    function setActive(){
        let navbar = document.getElementById('nav-bar');
        let a_tags = document.getElementsByTagName('a');

        for(i=0; i<a_tags.length; i++){
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if(document.location.href.indexOf(file_name) >= 0){
                a_tags[i].classList.add('active');
            }
        }
    }
    
    // register
    let register_form = document.getElementById('register-form');
    
    register_form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        let data = new FormData();
        
        data.append('name', register_form.elements['name'].value);
        data.append('email', register_form.elements['email'].value);
        data.append('phonenum', register_form.elements['phonenum'].value);
        data.append('address', register_form.elements['address'].value);
        data.append('pincode', register_form.elements['pincode'].value);
        data.append('dob', register_form.elements['dob'].value);
        data.append('pass', register_form.elements['pass'].value);
        data.append('cpass', register_form.elements['cpass'].value);
        data.append('profile', register_form.elements['profile'].files[0]);
        data.append('register', '');
        
        var myModal = document.getElementById('registerModal') ;
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);
        
        
        xhr.onload = function(){
            if(this.responseText == 'pass_mismatch'){
                alert('error', 'Password Mismatch!');
            }
            else if(this.responseText == 'email_already'){
                alert('error', 'Email is already registered!');
            }
            else if(this.responseText == 'phone_already'){
                alert('error', 'Phone number is already registered!');
            }
            else if(this.responseText == 'inv_img'){
                alert('error', 'Only JPG, WEBP & PNG are allowed!');
            }
            else if(this.responseText == 'upd_failed'){
                alert('error', 'Image upload failed!');
            }
            else if(this.responseText == 'mail_failed'){
                alert('error', 'Cannot send confirmation Email! Server down');
            }
            else if(this.responseText == 'ins_failed'){
                alert('error', 'Registration failed! Server down');
            }
            else {
                alert('success', 'Registration successful. Confirmation link sent to email!');
                register_form.reset();
            }
        }
        xhr.send(data);
    });

    // login
    let login_form = document.getElementById('login-form');
    
    login_form.addEventListener('submit', (e) => {
        e.preventDefault();

        let data = new FormData();
        
        data.append('email_mob', login_form.elements['email_mob'].value);
        data.append('pass', login_form.elements['pass'].value);
        data.append('login', '');

        var myModal = document.getElementById('loginModal') ;
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function(){ 
            if(this.responseText == 'inv_email_mob'){
                alert('error', 'Invalid Email or Mobile number!');
            }
            else if(this.responseText == 'not_verified'){
                alert('error', 'Email is not verified!');
            }
            else if(this.responseText == 'inactive'){
                alert('error', 'Account suspended! Please contact admin!');
            }
            else if(this.responseText == 'invalid_pass'){
                alert('error', 'Incorrect Password!');
            }
            else {
                let fileUrl = window.location.href.split('/').pop().split('?').shift();
                if(fileUrl == 'facilities_details.php'){
                    window.location =  window.location.href;
                }
                else{
                    window.location = window.location.pathname;
                }
                
            }
        }
        xhr.send(data);
    });

    // forgot password
    let forgot_form = document.getElementById('forgot-form');
    
    forgot_form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        let data = new FormData();
        
        data.append('email', forgot_form.elements['email'].value);
        data.append('forgot_pass', '');
        
        var myModal = document.getElementById('forgotModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);
        
        xhr.onload = function(){ 
            if(this.responseText == 'inv_email'){
                alert('error', 'Invalid Email!');
            }
            else if(this.responseText == 'not_verified'){
                alert('error', 'Email is not verified! Please contact Admin');
            }
            else if(this.responseText == 'inactive'){
                alert('error', 'Account suspended! Please contact admin!');
            }
            else if(this.responseText == 'mail_failed'){
                alert('error', 'Cannot send email. Server Down!');
            }
            else if(this.responseText == 'upd_failed'){
                alert('error', 'Account recovery failed. Server Down!');
            }
            else{
                alert('success', 'Reset Link sent to your Email!');
                forgot_form.reset(); 
            }
        }
        xhr.send(data);
    });

    function checkLoginToReserve(status, facility_id)
    {
        if(status){
            window.location.href='confirm_reservation.php?id='+facility_id;
        }
        else {
            alert('error', 'Please login to reserve now!');
        }
    }
    
    setActive();
    
</script>