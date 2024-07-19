<?php
session_start();
include_once("admin/class/adminback.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$obj = new adminback();

$cata_info = $obj->p_display_catagory();
$cataDatas = array();
while ($data = mysqli_fetch_assoc($cata_info)) {
    $cataDatas[] = $data;
}

if (isset($_SESSION['user_id'])) {
    header("location:userprofile.php");
}

if (isset($_POST['u_pass_recover'])) {
    $recover_email = $_POST['recover_email'];
    $rec_row = $obj->user_password_recover($recover_email);

    $num_row = mysqli_num_rows($rec_row);
    $rec_msg = "";

    if ($num_row > 0) {
        $rec_result = mysqli_fetch_assoc($rec_row);

        $rec_id = $rec_result['user_id'];
        $rec_name = $rec_result['user_firstname'];
        $rec_email = $rec_result['user_email'];

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreplyfruitsbazar.in.net@gmail.com';           // SMTP username
            $mail->Password   = 'lkfikmbhvecnqagz';                // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
            
            $mail->Port       = 587;                               // TCP port to connect to

            // Recipients
            $mail->setFrom('noreplyfruitsbazar.in.net@gmail.com', 'Fruits Bazar'); // Use a generic no-reply email address and custom name
            $mail->addAddress($rec_email, $rec_name);              // Add a recipient

            // Content
            $mail->isHTML(true);                                   // Set email format to HTML
            $mail->Subject = 'Recover Password From Fruits Bazar';
            $mail->Body    = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #3c763d;
            padding: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            color: #ffffff;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
            color: #333333;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            text-align: center;
            background-color: #5cb85c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #3c763d;
            color: #ffffff;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Fruits Bazar</h2>
        </div>
        <div class='content'>
            <p>Dear {$rec_name},</p>
            <p>Please click the button below to reset your password:</p>
            <a href='http://localhost/fruitbazar/user_password_update.php?status=update&id={$rec_id}' class='button'>Reset Password</a>
            <p>This is a computer-generated email, please do not reply to this email.</p>
            <p>If you have any questions, kindly contact our support team from the contact us page on the website.</p>
            <P>Thank you,<br>Fruits Bazar Team</P>
            
        </div>
        <div class='footer'>
            <p>&copy; " . date('Y') . " Fruits Bazar. All rights reserved.</p>
        </div>
    </div>
</body>
</html>";

            $mail->AltBody = "Dear {$rec_name},\n\n
Please click the link below to reset your password:\n
http://localhost/fruitbazar/user_password_update.php?status=update&id={$rec_id}\n\n
This is a computer-generated email, please do not reply to this email.\n
If you have any questions, kindly contact our support team from the contact us page on the website.\n\n
Thank you,\n
Fruits Bazar Team\n
© " . date('Y') . " Fruits Bazar. All rights reserved.";

            $mail->send();
            $rec_msg = "Please check your email and reset your password.";
        } catch (Exception $e) {
            $rec_msg = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        $rec_msg = "Sorry! You do not have an account.";
    }
}
?>

<?php
include_once("includes/head.php");
?>

<body class="biolife-body">
    <!-- Preloader -->

    <?php
    include_once("includes/preloader.php");
    ?>

    <!-- HEADER -->
    <header id="header" class="header-area style-01 layout-03">

        <?php
        include_once("includes/header_top.php");
        ?>

        <?php
        include_once("includes/header_middle.php");
        ?>

        <?php
        include_once("includes/header_bottom.php");
        ?>

    </header>

    <!-- Page Contain -->
    <div class="page-contain">

        <!-- Main content -->
        <div id="main-content" class="main-content">

            <div class="container">
                <h2 class="text-center">Password Recovery</h2>

                <h4 class="text-danger">
                    <?php
                    if (isset($rec_msg)) {
                        echo $rec_msg;
                    }
                    ?>
                </h4>
                <div class="row">

                    <!--Form Sign In-->
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="signin-container">
                            <form action="" name="frm-login" method="post">
                                <p class="form-row">
                                    <label for="email">Email</label>
                                    <input type="email" id="fid-name" name="recover_email" class="txt-input">
                                </p>

                                <p class="wrap-btn">
                                    <input type="submit" value="Recover Password" name="u_pass_recover" class="btn btn-success">
                                </p>
                            </form>
                        </div>
                    </div>

                    <!--Go to Register form-->
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="register-in-container">
                            <div class="intro">
                                <h4 class="box-title">New Customer?</h4>
                                <p class="sub-title">Create an account with us and you’ll be able to:</p>
                                <ul class="lis">
                                    <li>Check out faster</li>
                                    <li>Save multiple shipping addresses</li>
                                    <li>Access your order history</li>
                                    <li>Track new orders</li>
                                    <li>Save items to your Wishlist</li>
                                </ul>
                                <a href="user_register.php" class="btn btn-bold">Create an account</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- FOOTER -->

    <?php
    include_once("includes/footer.php");
    ?>

    <!--Footer For Mobile-->
    <?php
    include_once("includes/mobile_footer.php");
    ?>

    <?php
    include_once("includes/mobile_global.php")
    ?>

    <!-- Scroll Top Button -->
    <a class="btn-scroll-top"><i class="biolife-icon icon-left-arrow"></i></a>

    <?php
    include_once("includes/script.php")
    ?>
</body>

</html>
