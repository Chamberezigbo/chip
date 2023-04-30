<!-- php code  -->
<?php
// project docs has too image uploads for this project first upload out side the user-dashboard is for kyc and profile only //
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require './process/pdo.php';
require('./process/mail.php');
require 'assets/octaValidate-PHP-main/src/Validate.php';
if (isset($_GET['ref'])) {
     # code...
     $ref = $_GET['ref'];
} else {
     $ref = null;
}


use Validate\octaValidate;

$db = new DatabaseClass();

//set configuration
$options = array(
     "stripTags" => true,
     "strictMode" => true
);
//create new instance
$myForm = new octaValidate('register', $options);
//define rules for each form input name
$valRules = array(
     "email" => array(
          ["R", "Your Email Address is required"],
          ["EMAIL", "Your Email Address is invalid!"]
     ),
     "firstName" => array(
          ["R", "Your  is required"],
          ["ALPHA_SPACES", "first name must have spaces"]
     ),
     "lastName" => array(
          ["R", "Your last name is required"],
          ["ALPHA_SPACES", "last name must have spaces"]
     ),
     "country" => array(
          ["R", "Your country is required"],
          ["ALPHA_SPACES", "country must have spaces"]
     ),
     "phone" => array(
          ["R", "Your phone number is required"],
          ["DIGITS", "Phone number must be digits"]
     ),
     "zipCode" => array(
          ["R", "Your zip code is required"],
          ["DIGITS", "zip code must be digits"]
     ),
     "pass" => array(
          ["R", "Your password is required"],
          ["PWD", "Password must contain a capital, lowercase"]
     ),
     "confirmPass" => array(
          ["R", "Your password is required"],
          ["EQUALTO", "pass", "password must equal to password",]
     ),
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     //begin validation on form fields from $_POST array
     if ($myForm->validateFields($valRules, $_POST)) {
          //Selecting a single row!//
          if (isset($ref)) {
               $result = $db->SelectOne("SELECT * FROM users WHERE email = :email", ['email' => $ref]);
               if ($result) {
                    $bonusAmount = '5';
                    $bonusNiretion = "Referal Bonus";
                    $bonusQuery = "INSERT INTO bonus (userId, amount,nirration,date) VALUES(:userId, :amount, :nirration, :date)";
                    $bonusData = [
                         "userId" => $result['user_id'],
                         "amount" => $bonusAmount,
                         "nirration" => $bonusNiretion,
                         "date" =>  time()
                    ];
                    $insertBonus = $db->Insert($bonusQuery, $bonusData);
                    $balance = $result['balance'];
                    $totalRef = $result['total_ref_bonus'];
                    $totalRef = $totalRef + 5;
                    $balance = $balance + $totalRef;
                    $secondResult = $db->Update(
                         "UPDATE users SET balance = :balance, total_ref_bonus = :bonus WHERE email = :email",
                         ['balance' => $balance, 'bonus' => $totalRef, 'email' => $ref]
                    );
               } else {
                    $ref = NULL;
               }
          };

          $result = $db->SelectOne("SELECT email FROM users WHERE email = :email", ['email' => $_POST['email']]);

          //If $row is FALSE, then no row was returned.
          if ($result) {
               $_SESSION['error'] = 1;
               $_SESSION['errorMassage'] = "Email has been taken";
               $errMsg = array(
                    'register' => array(
                         'email' => 'Email has been taken'
                    )
               );
               print('<script>
                    document.addEventListener("DOMContentLoaded", function(){
                         showErrors(' . json_encode($errMsg) . ');
                    });</script>');
          } else {
               //process form data here //
               $firstName = $_POST['firstName'];
               $lastName = $_POST['lastName'];
               $email = $_POST['email'];
               $phone = $_POST['phone'];
               $password = $_POST['pass'];
               $country = $_POST['country'];
               $zipCode = $_POST['zipCode'];
               $gender = $_POST['gender'];
               $balance = 5;
               $user_id = md5(time() . $email);
               // generate random otp for the user //
               $digits = 4;
               $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
               // end of random number //
               $query =
                    "INSERT INTO users (	user_id, first_name,last_name,zip_code,gender,email, password, phone, country,balance, referral,otp)
               VALUES(:user_id, :first_name, :last_name,:zip_code,:gender, :email, :password, :phone, :country, :balance,:referral,:otp)";
               $data = [
                    'user_id' => $user_id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'zip_code' => $zipCode,
                    'gender' => $gender,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'country' => $country,
                    'balance' => $balance,
                    'referral' => $ref,
                    'otp' => $otp,
               ];

               $result = $db->Insert($query, $data);
               if ($result) {
                    $_SESSION['auth'] = true;
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (40 * 60);
                    $_SESSION['user_id'] = $user_id;
                    $subject = "Thanks for signing up";
                    sendMail($email, $firstName, $subject, str_replace(["##fullname##", "##username##", '##password##', '##otp##'], [$firstName, $email, $password, $otp], file_get_contents("welcom-email.php")));
                    header("Location:user-dash/");
                    exit();
               } else {
                    $_SESSION['error'] = 1;
                    $_SESSION['errorMassage '] = "Signup was not successful";
                    header("Location:signup.php");
                    exit();
               };
          };
     } else {
          //return errors

          print('<script>
               document.addEventListener("DOMContentLoaded", function(){
                    showErrors(' . json_encode($myForm->getErrors()) . ');
          });</script>');
     }
}



?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Chipper Investment is the best and reliable invesment company with over 1000 users.">
     <title>SignUp - Chipper Investment</title>
     <link rel="icon" href="images/favicon.ico" type="image">
     <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
     <!-- <link rel="icon" href="images/favicon.ico" type="image"> -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="styles/login.css" />
     <link rel="stylesheet" href="styles/style.css" />
     <link href="assets/css/theme.css" rel="stylesheet" />
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Unbounded&display=swap" rel="stylesheet">
     <!-- toaster -->
     <script src="js/jquery.min.js"></script>
     <link rel="stylesheet" href="assets/toastr-master/build/toastr.min.css">
</head>

<body>







     <!-- =====================navbar section====================== -->
     <div class="">
          <!-- coin market cap marquee -->
          <script type="text/javascript" src="https://files.coinmarketcap.com/static/widget/coinMarquee.js"></script>
          <div id="coinmarketcap-widget-marquee" coins="1,1027,825,74,5426,2,14205,5994,1958,1839,3513,6210,18814,52,131,5370,20314,4172" currency="USD" theme="dark" border="none" transparent="true" show-symbol-logo="true" class="fixed-top nav-position-coin"></div>
          <!-- coin market cap marquee end -->
          <nav class="navbar navbar-expand-lg nav-position fixed-top">
               <div class="container">
                    <!-- <hr class="nav-line"> -->
                    <a class="navbar-brand main-nav" href="#">CHIPPER.</a>
                    <div class="d-block d-sm-none">
                         <button class="btn btn-right" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class='bx bx-menu bx-md' style="color: white;"></i></button>
                         <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                              <div class="offcanvas-header">
                                   <h5 class="offcanvas-title" id="offcanvasRightLabel" style="font-family: 'Unbounded', cursive;">CHIPPER.</h5>
                                   <button type="button" class="btn-close close-right" data-bs-dismiss="offcanvas" aria-label="Close"><i class='bx bx-x bx-sm' style="color: white;"></i></button>
                              </div>
                              <div class="offcanvas-body">
                                   <ul class="navbar-nav">
                                        <li class="nav-item">
                                             <a class="nav-link" aria-current="page" href="index.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">HOME</a>
                                        </li>
                                        <hr style="background-color: white;">
                                        <li class="nav-item">
                                             <a class="nav-link" href="about.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">ABOUT</a>
                                        </li>
                                        <hr style="background-color: white;">
                                        <li class="nav-item">
                                             <a class="nav-link" href="info.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">INFO</a>
                                        </li>
                                        <hr style="background-color: white;">
                                        <li class="nav-item">
                                             <a class="nav-link" href="aff.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">AFFILIATE</a>
                                        </li>
                                        <hr style="background-color: white;">
                                        <li class="nav-item">
                                             <a class="nav-link" href="terms.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">T&C</a>
                                        </li>
                                        <hr style="background-color: white;">
                                        <li class="nav-item">
                                             <a class="nav-link" href="faq.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">FAQs</a>
                                        </li>
                                        <hr style="background-color: white;">
                                        <li class="nav-item">
                                             <a class="nav-link" href="contact.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;">CONTACT</a>
                                        </li>
                                        <!-- <li class="nav-item">
                      <a class="nav-link" href="#" style="color: #e7e9ea;font-family:overpass-variable;">GIVE</a>
                    </li> -->
                                   </ul>
                              </div>
                         </div>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                         <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll nav-ul" style="--bs-scroll-height: 100px;">
                              <li class="nav-item">
                                   <a class="nav-link" aria-current="page" style="color: #e7e9ea;" href="index.html">HOME</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="about.html" style="color: #e7e9ea;">ABOUT</a>
                              </li>
                              <!-- <li class="nav-item">
                  <a class="nav-link" href="#" style="color: #e7e9ea;font-family:overpass-variable;"></a>
                </li> -->
                              <li class="nav-item">
                                   <a class="nav-link" href="info.html" style="color: #e7e9ea;">INFO</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="aff.html" style="color: #e7e9ea;">AFFILIATE</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="terms.html" style="color: #e7e9ea;">T&C</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="faq.html" style="color: #e7e9ea;">FAQs</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="contact.html" style="color: #e7e9ea;">CONTACT</a>
                              </li>
                              <!-- <li class="nav-item">
                  <a class="nav-link" href="#" style="color: #e7e9ea;font-family:overpass-variable;">GIVE</a>
                </li> -->
                         </ul>
                    </div>
               </div>
          </nav>



     </div>
     <!-- =====================navbar section====================== -->

     <!-- particles -->
     <section class="particle-body">
          <div class="particle-text ar">
               <h1>USER <span>REGISTERATION </span></h1>
               <p><span>Home</span> / CREATE AN ACCOUNT</p>
          </div>
          <div id="particles-js"></div>
     </section>
     <!-- particles end -->


     <!-- ==========login========== -->
     <div class="">
          <div class="container" style="margin-top: 1rem;margin-bottom: 7%;font-family: 'Unbounded', cursive;">
               <form action="" method="post" id="register" loadnovalidate>
                    <div class="row">
                         <div class="col-md-12" style="margin-top:3rem;margin-bottom: 2.5rem;">
                              <h4 class="text-white">User Registration <i class='bx bx-lock bx-sm' style="color: #fb8b23;"></i></h4>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="text" value="<?php (isset($_POST) && isset($_POST['firstName'])) ? print($_POST['firstName']) : '' ?>" class="form-control log-details" placeholder="First Name" aria-label="firstname" name="firstName" aria-describedby="basic-addon1" required>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="text" value="<?php (isset($_POST) && isset($_POST['lastName'])) ? print($_POST['lastName']) : '' ?>" class="form-control log-details" placeholder="Last Name" aria-label="lastname" name="lastName" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="email" value="<?php (isset($_POST) && isset($_POST['email'])) ? print($_POST['email']) : '' ?>" class="form-control log-details" placeholder="Email" name="email" aria-label="email" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <!-- <input type="text" class="form-control log-details" placeholder="Gender" aria-label="gender" aria-describedby="basic-addon1"> -->
                                   <select class="form-select log-details text-white" name="gender" id="inputGroupSelect01" style="font-family: 'Unbounded', cursive;background-color: black;font-size: 14px;">
                                        <option selected class="">Gender</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="2">Other</option>
                                   </select>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="text" class="form-control log-details" name="country" placeholder="Country" aria-label="country" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="number" class="form-control log-details" name="phone" placeholder="Mobile Number" aria-label="number" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="number" class="form-control log-details" name="zipCode" placeholder="Zip code" aria-label="zipcode" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="password" class="form-control log-details" name="pass" placeholder="Password" aria-label="password" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input type="password" class="form-control log-details" name="confirmPass" placeholder="Confirm Password" aria-label="password" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="input-group mb-3">
                                   <input disabled type="text" class="form-control log-details" name="referral" aria-label="referralid" placeholder=" <?php (isset($_GET) && isset($_GET['ref'])) ? print($ref) : print("Referral ID") ?>" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <div class="container">
                              <div class="form-check form-switch">
                                   <input class="form-check-input input-switch" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                   <label class="form-check-label" for="flexSwitchCheckDefault" style="font-family: 'Unbounded', cursive;font-size: 9px;color: grey;"> I am 18 years of age or older</label>
                              </div>
                              <div class="form-check form-switch">
                                   <input class="form-check-input input-switch" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                   <label class="form-check-label" for="flexSwitchCheckDefault" style="font-family: 'Unbounded', cursive;font-size: 9px;color: grey;">I agree to Chipper | Your Trusted Bitcoin Mining and Investment Company <a href="" style="color: #fb8b23;text-decoration: none;">Terms and conditions</a></label>
                              </div>
                              <div class="form-check form-switch">
                                   <input class="form-check-input input-switch" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                   <label class="form-check-label" for="flexSwitchCheckDefault" style="font-family: 'Unbounded', cursive;font-size: 9px;color: grey;">I agree to receive Chipper | Your Trusted Bitcoin Mining and Investment Company and third party emails</label>
                              </div>
                         </div>
                         <figcaption class="figure-caption text-start" style="font-size: 9px;"><a href="login.php" style="text-decoration: none;color: #fb8b23;">Already have an Account?</a></figcaption>
                         <div style="text-align: center;margin-top: 4.5rem;margin-bottom: 2rem;">
                              <button class="btn log-btn" style="font-family: 'Unbounded', cursive;">Register</button>
                         </div>
                    </div>
               </form>
          </div>
     </div>
     <!-- =============login end================ -->




     <div class="area">
          <div class="ar">
               <div class="container" style="font-family: 'Unbounded', cursive;">
                    <div class="row">
                         <div class="col-md-12 text-center gs-text">
                              <h2>GET STARTED TODAY WITH BITCOIN INVESTMENT</h2>
                              <p>Open account for free and start trading Bitcoins!</p>
                              <button class="btn gs-btn"><a href="signup.html" style="font-family: 'Unbounded', cursive;text-decoration: none;color: white;">GET STARTED</a></button>
                         </div>
                    </div>
               </div>
          </div>
     </div>



     <!-- ======footer======= -->
     <section class="footer-background s-top ">
          <div class="container ">
               <div class="row footer-row">
                    <div class="col-md-3">
                         <h4>Quick Links</h4>
                         <ul class="footer-list">
                              <li><a href="index.html">Home</a></li>
                              <li><a href="about.html">About</a></li>
                              <li><a href="faq.html">Faqs</a></li>
                              <li><a href="terms.html">Terms & Conditions</a></li>
                              <li><a href="contact.html">Contact</a></li>
                         </ul>
                    </div>
                    <div class="col-md-3">
                         <h4>Helps & Support</h4>
                         <ul class="footer-list">
                              <li><a href="https://cointelegraph.com/bitcoin-for-beginners/what-are-cryptocurrencies">What is Bitcoin?</a></li>
                              <li><a href="https://www.investopedia.com/tech/how-to-buy-bitcoin/">How to buy Bitcoin</a></li>
                              <li><a href="signup.html">Register</a></li>
                              <li><a href="login.html">Login</a></li>
                              <li><a href="reset.html">Forgot Password</a></li>
                         </ul>
                    </div>
                    <div class="col-md-3">
                         <h4>Contact Us</h4>
                         <p class="footer-address">
                              <a class="nav-link" href="#"><i class='bx bx-location-plus'></i> 5 Preston Court, Burton, United Kingdom.</a>
                              <a class="nav-link" href="tel:+17708829075"><i class='bx bx-phone'></i> +1 770-882-9075</a>
                              <a class="nav-link" href="mailto://pastorchima2@gmail.com"> <i class='bx bx-envelope'></i> support@chipper.com</a>
                         </p>
                    </div>
                    <!-- <div class="col-md-3">
              <h4>ONLINE SERVICE</h4>
              <p class="footer-social-text" >Worship with us online through these platforms.</p>
              <div class="d-flex footer-social">
                  <a class="nav-link" href="https://www.facebook.com" title="Facebook"><i class='bx bxl-facebook-circle bx-md'></i></a>
                  <a class="nav-link" href="https://www.youtube.com" title="Youtube"></i> <i class='bx bxl-youtube bx-md'></i></a>
                  <a class="nav-link" href="https://www.instagram.com" title="Instagram"><i class='bx bxl-instagram-alt bx-md ml-5'></i></a>
                  <a class="nav-link" href="https://www.twitter.com" title="Twitter"><i class='bx bxl-twitter bx-md'></i></a>
              </div>
            </div> -->
                    <div class="col-md-3">
                         <h4>Subscribe To Newsletter</h4>
                         <div class="message form-field">
                              <textarea name="cMessage" id="cMessage" class="prayer-input" placeholder="Email"></textarea>
                         </div>
                         <a href="#" target="_blank" class="btn btn--footer" style="font-family: 'Unbounded', cursive;">Send</a>
                    </div>
                    <hr style="color: rgba(255, 255, 255, 0.5);margin-top: 1rem">
                    <div class="text-center">
                         <p style="font-size: 0.9rem;color: rgba(255, 255, 255, 0.5);">© 2023 Chipper Investment Company</p>
                    </div>
               </div>
          </div>
     </section>
     <!-- ======footer end======= -->
















     <script src="particles.js"></script>
     <script src="app.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
     <script src="https://kit.fontawesome.com/678ed16258.js" crossorigin="anonymous"></script>
     <script src="assets/octaValidate-PHP-main//frontend/helper.js"></script>
     <script src="assets/toastr-master/build/toastr.min.js"></script>
</body>

</html>