<?php
//check if session is started already
if (session_status() === PHP_SESSION_NONE)
     session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     require 'process/pdo.php';
     $db = new DatabaseClass();
     $email = trim($_POST['loginUsername']);
     $password = $_POST['loginPassword'];
     // $date = date("Y/m/d");
     // $time = date("h:i:sa");
     // $fullDate = $date . " " . $time;

     if ($email == "admin@mail.com" && $password == "admin12345") {
          $_SESSION['auth'] = true;
          $_SESSION['start'] = time();
          $_SESSION['expire'] = $_SESSION['start'] + (40 * 60);
          print('<script>
                    document.addEventListener("DOMContentLoaded", function() {
                    toastr.success("You have been loged in as APA Admin");
                    })
          </script>');
          //? need to change the rediraction // 
          header("Location:admin/");
          exit();
     } else {
          // $sql = "SELECT * FROM users WHERE username = '$email' OR email = '$email'";
          // $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
          $result = $db->SelectOne("SELECT * FROM users WHERE email = :email OR email = :email", ['email' => $email]);
          if ($result) {
               if ($password == $result['password']) {
                    // $sql = "UPDATE  users SET lastSeen = '$fullDate'  WHERE email= '$email'";
                    // $resultSeen = mysqli_query($conn, $sql);
                    // if ($resultSeen) {
                    $_SESSION['auth'] = true;
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (40 * 60);
                    $_SESSION["user_id"] = $result['user_id'];
                    //? $_SESSION['balance'] =  $result['bonus_balance'];//
                    //? $_SESSION['balance'] =  $result['referal_balance'];//
                    print('<script>
                         document.addEventListener("DOMContentLoaded", function() {
                         toastr.success("Welcome youve been login");
                         })
                    </script>');
                    header("Location:user-dash/");
                    exit();
                    // } else {
                    //      $_SESSION['error'] = 1;
                    //      $_SESSION['errorMassage'] = "Error updating last seen";
                    //      header("Location:index.php");
                    //      exit();
                    // }
               } else {
                    print('<script>
                              document.addEventListener("DOMContentLoaded", function() {
                              toastr.error("Wrong password");
                              toastr.clear()
                         })
                    </script>');
               }
          } else {
               print('<script>
                         document.addEventListener("DOMContentLoaded", function() {
                         toastr.error("Wrong password");
                         setTimeout(function() {
                              toastr.clear()
                         }, 3000);
                         
                    })
               </script>');
          }
     }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Chipper Investment is the best and reliable invesment company with over 1000 users.">
     <title>Login - Chipper Investment</title>
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
                                             <a class="nav-link" aria-current="page" href="index.html" style="font-family: 'Unbounded', cursive;color: #e7e9ea;;">HOME</a>
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
                                             <a class="nav-link" href="faq.html" style="font-family: 'Unbounded', cursive;color: color: #e7e9ea;;">FAQs</a>
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
               <h1>USER <span>LOGIN </span></h1>
               <p><span>Home</span> / LOGIN</p>
          </div>
          <div id="particles-js"></div>
     </section>
     <!-- particles end -->

     <!-- ==========login========== -->
     <div class="">
          <form action="" method="post">
               <div class="container " style="margin-top: 1rem;margin-bottom: 7%;font-family: 'Unbounded', cursive;">
                    <div class="row">
                         <div class="col-md-12" style="margin-top:3rem;margin-bottom: 2.5rem;">
                              <h4 class="text-white">Login Authentication <i class='bx bx-lock bx-sm' style="color: #fb8b23;"></i></h4>
                         </div>
                         <div class="col-md-12">
                              <div class="input-group mb-3">
                                   <input type="email" class="form-control log-details" name="loginUsername" placeholder="Email" aria-label="email" aria-describedby="basic-addon1" autocomplete="off">
                              </div>
                         </div>
                         <div class="col-md-12" style="margin-top:3rem;">
                              <div class="input-group mb-3">
                                   <input type="password" class="form-control log-details" name="loginPassword" placeholder="Password" aria-label="password" aria-describedby="basic-addon1">
                              </div>
                         </div>
                         <figcaption class="figure-caption text-end" style="font-size: 9px;"><a href="reset.html" style="color: #fb8b23;text-decoration: none;">Forgot Password?</a></figcaption>
                         <div style="text-align: center;margin-top: 4.5rem;margin-bottom: 2rem;">
                              <a href=""><button class="btn log-btn" style="font-family: 'Unbounded', cursive;">Login</button></a>
                         </div>
                    </div>
               </div>
          </form>
          <!-- =============login end================ -->



          <div class="area">
               <div class="ar">
                    <div class="container" style="font-family: 'Unbounded', cursive;">
                         <div class="row">
                              <div class="col-md-12 text-center gs-text">
                                   <h2>GET STARTED TODAY WITH BITCOIN INVESTMENT</h2>
                                   <p>Open account for free and start trading Bitcoins!</p>
                                   <button class="btn gs-btn"><a href="gstarted.html" style="font-family: 'Unbounded', cursive;text-decoration: none;color: white;">GET STARTED</a></button>
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
                                   <li><a href="signup.php">Register</a></li>
                                   <li><a href="login.php">Login</a></li>
                                   <li><a href="reset.php">Forgot Password</a></li>
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
          <script src="assets/toastr-master/build/toastr.min.js"></script>
</body>

</html>