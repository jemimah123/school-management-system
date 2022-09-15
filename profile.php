<?php


session_start();
require_once('config/config.php');/* Load Config File */
require_once('config/checklogin.php');/* Load Checklogin */
checklogin();/* Invoke Check Login, Prevent Watery Log Ins & Session Hijacking */

/* Update Profile */
if (isset($_POST['update_profile'])) {
    $admin_email = $_POST['admin_email'];
    $admin_id = $_SESSION['admin_id'];
    $new_password = sha1(md5($_POST['new_password']));
    $confirm_password = sha1(md5($_POST['confirm_password']));

    /* Check If Has & Digest Match */
    if ($confirm_password != $new_password) {
        $err = "Passwords Does Not Match";
    } else {
        /* Log This Transaction */
        $sql = "UPDATE admin SET admin_email =?, admin_password = ? WHERE admin_id = ?";
        $prepare = $mysqli->prepare($sql);
        $bind = $prepare->bind_param('sss', $admin_email, $confirm_password, $admin_id);
        $prepare->execute();
        if ($prepare) {
            $success = "Profile Details Updated";
        } else {
            $err = "Failed!, Please Try Again";
        }
    }
}
require_once('partials/head.php');/* Load Head Partial */
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php');
        $admin_id = $_SESSION['admin_id'];
        $ret = "SELECT * FROM admin WHERE admin_id = '$admin_id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($user = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $user->admin_email; ?> Profile</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item active">Profile </li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Profile Settings</h3>
                            </div> <!-- /.card-body -->
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Email</label>
                                            <input type="text" name="admin_email" value="<?php echo $user->admin_email; ?>" required class="form-control">

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>New Password</label>
                                            <input type="password" name="new_password" required class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Confirm New Password</label>
                                            <input type="password" name="confirm_password" required class="form-control">
                                        </div>

                                    </div>
                                    <div class="text-right">
                                        <button name="update_profile" class="btn btn-primary" type="submit">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php } ?>
        <!-- Main Footer -->
        <?php require_once('partials/footer.php'); ?>
    </div>
    <!-- ./wrapper -->
    <?php require_once('partials/scripts.php'); ?>
</body>

</html>