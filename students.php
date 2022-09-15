<?php

session_start();
require_once('config/config.php');/* Load Config File */
require_once('config/checklogin.php');/* Load Checklogin */
checklogin();/* Invoke Check Login, Prevent Watery Log Ins & Session Hijacking */

/* Add Student */
if (isset($_POST['add_student'])) {
    $student_admno = $_POST['student_admno'];
    $student_class_id = $_POST['student_class_id'];
    $student_name = $_POST['student_name'];

    /* Persist */
    $sql = "INSERT INTO student (student_admno, student_class_id, student_name) VALUES(?,?,?)";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param('sss', $student_admno, $student_class_id, $student_name);
    $prepare->execute();
    if ($prepare) {
        $success = "$student_admno, $student_name, Added";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Update Student */
if (isset($_POST['update_student'])) {
    $student_admno = $_POST['student_admno'];
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];

    /* Persist */
    $sql = "UPDATE student  SET student_admno =?, student_name =? WHERE student_id =?";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param('sss', $student_admno, $student_name, $student_id);
    $prepare->execute();
    if ($prepare) {
        $success = "$student_admno, $student_name, Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Delete Student */
if (isset($_POST['delete'])) {
    $student_id = $_POST['student_id'];

    /* Persist */
    $sql = "DELETE FROM student WHERE  student_id =?";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param('s', $student_id);
    $prepare->execute();
    if ($prepare) {
        $success = "Student Detaails Deleted";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-bold">Students</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active">Students</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Add Student</button>
                    </div>
                    <hr>
                    <!-- Add Modal -->
                    <div class="modal fade" id="add_modal">
                        <div class="modal-dialog  modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Fill All Values </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="">Full Name</label>
                                                    <input type="text" required name="student_name" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="">Admission Number</label>
                                                    <input type="text" required name="student_admno" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="">Class Enrolled</label>
                                                    <select class="form-control" name="student_class_id">
                                                        <?php
                                                        $ret = "SELECT * FROM class ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($class = $res->fetch_object()) {
                                                        ?>
                                                            <option value="<?php echo $class->class_id; ?>"><?php echo $class->class_code . ' - ' . $class->class_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" name="add_student" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                    <div class="row">
                        <div class="col-12">
                            <table id="" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Admission Number </th>
                                        <th>Class Enrolled</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM student s 
                                    INNER JOIN class c
                                     ON s.student_class_id = c.class_id";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($std = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $std->student_name; ?></td>
                                            <td>
                                                <?php echo $std->student_admno; ?>
                                            </td>
                                            <td>
                                                Code : <?php echo $std->class_code . '<br> Name : ' . $std->class_name; ?>
                                            </td>
                                            <td>
                                                <a class="badge badge-primary" data-toggle="modal" href="#edit-<?php echo $std->student_id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Update
                                                </a>
                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $std->student_id; ?>">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>
                                                <!-- Update Modal -->
                                                <div class="modal fade" id="edit-<?php echo $std->student_id; ?>">
                                                    <div class="modal-dialog  modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Fill All Values </h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Full Name</label>
                                                                                <input type="text" required value="<?php echo $std->student_name; ?>" name="student_name" class="form-control">
                                                                                <input type="hidden" value="<?php echo $std->student_id; ?>" required name="student_id" class="form-control">

                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Admission Number</label>
                                                                                <input type="text" required name="student_admno" value="<?php echo $std->student_admno; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <button type="submit" name="update_student" class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="delete-<?php echo $std->student_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center text-danger">
                                                                <form method="POST">
                                                                    <h4>Delete <?php echo $std->student_name; ?> ?</h4>
                                                                    <br>
                                                                    <p>Heads Up, You are about to delete <?php echo $std->student_name; ?>. This action is irrevisble.</p>
                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                    <input type="hidden" name="student_id" value="<?php echo $std->student_id; ?>">
                                                                    <input type="submit" class="text-center btn btn-danger" value="Delete" name="delete">
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Main Footer -->
        <?php require_once('partials/footer.php'); ?>
    </div>
    <?php require_once('partials/scripts.php'); ?>
</body>

</html>