<?php

session_start();
require_once('config/config.php'); /* Load Config  */
require_once('config/codeGen.php'); /* Load Code Generator File */

/* Load Password Reset */
if (isset($_POST['Reset_Password'])) {

    $admin_email = $_POST['admin_email'];
    $query = mysqli_query($mysqli, "SELECT * FROM admin WHERE admin_email = '" . $admin_email . "' ");
    $num_rows = mysqli_num_rows($query);
    if ($num_rows > 0) {
        $query = "UPDATE admin SET  admin_password =? WHERE  admin_email =? ";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ss', $sys_gen_password, $admin_email);
        $stmt->execute();
        if ($stmt) {
            $_SESSION['admin_email'] = $admin_email;
            $success = " Password Reset" && header("refresh:1; url=confirm_password");
        } else {
            $err = " Password reset failed";
        }
    } else {
        $err = "  User Account Does Not Exist";
    }
}

require_once('partials/head.php');

?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>School Management System</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Enter Your Email To Reset Password </p>

                <form method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="admin_email" required placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <button type="submit" name="Reset_Password" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p class="mb-1">
                    <a href="reset_password">I Remembered My Password</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <?php require_once('partials/scripts.php'); ?>

</body>

</html>