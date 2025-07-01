<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit'])) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $contno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    // Server-side email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } else if (!preg_match("/^[0-9]{10}$/", $contno)) {
        // Server-side phone number validation (must be 10 digits)
        $msg = "Invalid mobile number. It should be exactly 10 digits.";
    } else if (!preg_match("/^[a-zA-Z\s]+$/", $fname)) {
        // First name validation (only letters and spaces)
        $msg = "First Name can only contain letters and spaces.";
    } else if (!preg_match("/^[a-zA-Z\s]+$/", $lname)) {
        // Last name validation (only letters and spaces)
        $msg = "Last Name can only contain letters and spaces.";
    } else if (strlen($_POST['password']) < 8) {
        // Password validation (at least 8 characters)
        $msg = "Password must be at least 8 characters long.";
    } else {
        // Check if email or mobile number already exists
        $ret = mysqli_query($con, "SELECT Email FROM tbluser WHERE Email='$email' OR MobileNumber='$contno'");
        $result = mysqli_fetch_array($ret);
        if($result > 0) {
            $msg = "This email or Contact Number is already associated with another account.";
        } else {
            // Insert new user data into the database
            $query = mysqli_query($con, "INSERT INTO tbluser(FirstName, LastName, MobileNumber, Email, Password) 
                                        VALUES ('$fname', '$lname', '$contno', '$email', '$password')");
            if ($query) {
                $msg = "You have successfully registered.";
            } else {
                $msg = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Brownie Point|| Sign Up</title>
    <!-- Icon css link -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="vendors/linearicons/style.css" rel="stylesheet">
    <link href="vendors/flat-icon/flaticon.css" rel="stylesheet">
    <link href="vendors/stroke-icon/style.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Rev slider css -->
    <link href="vendors/revolution/css/settings.css" rel="stylesheet">
    <link href="vendors/revolution/css/layers.css" rel="stylesheet">
    <link href="vendors/revolution/css/navigation.css" rel="stylesheet">
    <link href="vendors/animate-css/animate.css" rel="stylesheet">

    <!-- Extra plugin css -->
    <link href="vendors/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="vendors/magnifc-popup/magnific-popup.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">

    <script type="text/javascript">
    function checkpass() {
        // Check if passwords match
        if (document.signup.password.value != document.signup.repeatpassword.value) {
            alert('Password and Repeat Password field does not match');
            document.signup.repeatpassword.focus();
            return false;
        }

        // Validate email format
        var email = document.signup.email.value;
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            alert('Please enter a valid email address');
            document.signup.email.focus();
            return false;
        }

        // Validate mobile number (10 digits)
        var mobilenumber = document.signup.mobilenumber.value;
        var mobilePattern = /^[0-9]{10}$/;
        if (!mobilePattern.test(mobilenumber)) {
            alert('Please enter a valid 10-digit mobile number');
            document.signup.mobilenumber.focus();
            return false;
        }

        // Validate first name (only letters and spaces)
        var firstname = document.signup.firstname.value;
        var fnamePattern = /^[a-zA-Z\s]+$/;
        if (!fnamePattern.test(firstname)) {
            alert('First Name can only contain letters and spaces');
            document.signup.firstname.focus();
            return false;
        }

        // Validate last name (only letters and spaces)
        var lastname = document.signup.lastname.value;
        var lnamePattern = /^[a-zA-Z\s]+$/;
        if (!lnamePattern.test(lastname)) {
            alert('Last Name can only contain letters and spaces');
            document.signup.lastname.focus();
            return false;
        }

        // Validate password (at least 8 characters)
        var password = document.signup.password.value;
        if (password.length < 8) {
            alert('Password must be at least 8 characters long');
            document.signup.password.focus();
            return false;
        }

        return true;
    }
    </script>

</head>
<body>

    <!--================Main Header Area =================-->
    <?php include_once('includes/header.php');?>
    <!--================End Main Header Area =================-->

    <section class="banner_area">
        <div class="container">
            <div class="banner_text">
                <h3>Registration Form</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="registration.php">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </section>

    <!--================Contact Form Area =================-->
    <section class="contact_form_area p_100">
        <div class="container">
            <div class="main_title">
                <h2>Registration Form!!</h2>
                <h5>Fill below details.</h5>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <p style="font-size:16px; color:blue" align="center"> 
                        <?php if($msg){
                            echo $msg;
                        }  ?> 
                    </p>
                    <form class="row contact_us_form" action="" name="signup" method="post" onsubmit="return checkpass();">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" name="firstname" required="true" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="lastname" name="lastname" required="true" placeholder="Last Name">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="mobilenumber" name="mobilenumber" required="true" maxlength="10" pattern="[0-9]{10}" placeholder="Mobile Number">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email" class="form-control" id="email" name="email" required="true" placeholder="Email address">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="password" class="form-control" id="password" name="password" required="true" placeholder="Password">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="password" class="form-control" id="repeatpassword" name="repeatpassword" required="true" placeholder="Repeat Password">
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" value="submit" name="submit" class="btn order_s_btn form-control">Submit Now</button>
                        </div>
                        <div class="form-group col-md-12">
                            <a href="login.php" class="btn order_s_btn form-control"><i class="ft-user"></i> Login</a> <strong>Already Have an account?</strong>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 offset-md-1">
                    <div class="contact_details">
                        <?php
                        $ret=mysqli_query($con,"select * from tblpage where PageType='contactus' ");
                        $cnt=1;
                        while ($row=mysqli_fetch_array($ret)) 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once('includes/footer.php');?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="vendors/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="vendors/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script src="vendors/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="vendors/revolution/js/extensions/revolution.extension.video.min.js"></script>
    <script src="vendors/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="vendors/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="vendors/magnifc-popup/jquery.magnific-popup.min.js"></script>
    <script src="vendors/datetime-picker/js/moment.min.js"></script>
    <script src="vendors/datetime-picker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="vendors/nice-select/js/jquery.nice-select.min.js"></script>
    <script src="vendors/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendors/lightbox/simpleLightbox.min.js"></script>
    <script src="js/gmaps.min.js"></script>
    <script src="js/map-active.js"></script>

    <script src="js/jquery.form.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/contact.js"></script>

    <script src="js/theme.js"></script>
</body>
</html>
