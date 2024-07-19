<?php
session_start();
include_once("admin/class/adminback.php");
$obj = new adminback();

// Fetching category information
$cata_info = $obj->p_display_catagory();
$cataDatas = array();
while ($data = mysqli_fetch_assoc($cata_info)) {
    $cataDatas[] = $data;
}

// Redirecting if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("location:userprofile.php");
    exit;
}

// Checking for update status
if (isset($_GET['status']) && $_GET['status'] == 'update') {
    $update_id = $_GET['id'];
}

// Handling password update form submission
if (isset($_POST['u_pass_recover'])) {
    $update_msg = $obj->update_user_password($_POST);
}
?>

<?php
include_once("includes/head.php");
?>

<body class="biolife-body">
    <!-- Preloader -->
    <?php include_once("includes/preloader.php"); ?>

    <!-- HEADER -->
    <header id="header" class="header-area style-01 layout-03">
        <?php
        include_once("includes/header_top.php");
        include_once("includes/header_middle.php");
        include_once("includes/header_bottom.php");
        ?>
    </header>

    <!-- Page Contain -->
    <div class="page-contain">
        <!-- Main content -->
        <div id="main-content" class="main-content">
            <div class="container">
                <h2 class="text-center">Update Password</h2>
                <h4 class="text-danger">
                    <?php
                    if (isset($update_msg)) {
                        echo htmlspecialchars($update_msg);
                    }
                    ?>
                </h4>
                <div class="row">
                    <!--Form Sign In-->
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="signin-container">
                            <form action="" name="frm-login" method="POST">
                                <input type="hidden" name="update_user_id" value="<?php echo htmlspecialchars($update_id); ?>">
                                <p class="form-row">
                                    <label for="user_password">New Password:</label>
                                    <input type="password" name="update_user_password" class="txt-input" required>
                                </p>
                                <p class="wrap-btn">
                                    <input type="submit" value="Update Password" name="u_pass_recover" class="btn btn-success">
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
    include_once("includes/mobile_footer.php");
    include_once("includes/mobile_global.php");
    ?>

    <!-- Scroll Top Button -->
    <a class="btn-scroll-top"><i class="biolife-icon icon-left-arrow"></i></a>

    <?php
    include_once("includes/script.php");
    ?>
</body>
</html>
