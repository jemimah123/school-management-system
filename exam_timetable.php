<?php
/*
 
session_start();
require_once('config/config.php');/* Load Config File */
require_once('config/checklogin.php');/* Load Checklogin */
checklogin();/* Invoke Check Login, Prevent Watery Log Ins & Session Hijacking */

/* Add Subject To Time Table */
if (isset($_POST['add_tt'])) {
    $tt_subject_id = $_POST['tt_subject_id'];
    $tt_exam_date = $_POST['tt_exam_date'];
    $tt_exam_start = $_POST['tt_exam_start'];
    $tt_exam_end = $_POST['tt_exam_end'];
    $tt_exam_invigilator  = $_POST['tt_exam_invigilator'];
    $tt_exam_class_id  = $_POST['tt_exam_class_id'];

    /* Log Transaction */
    $sql = "INSERT INTO exam_timetable (tt_subject_id, tt_exam_date, tt_exam_start,  tt_exam_end, tt_exam_invigilator, tt_exam_class_id) 
    VALUES(?,?,?,?,?,?)";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param(
        'ssssss',
        $tt_subject_id,
        $tt_exam_date,
        $tt_exam_start,
        $tt_exam_end,
        $tt_exam_invigilator,
        $tt_exam_class_id
    );
    $prepare->execute();
    if ($prepare) {
        $success = "Subject Added To Exam Time Table";
    } else {
        $err = "Failed!, Please Try Again";
    }
}

/* Update Subject To Time Table */
if (isset($_POST['update_tt'])) {
    $tt_id = $_POST['tt_id'];
    $tt_exam_date = $_POST['tt_exam_date'];
    $tt_exam_start = $_POST['tt_exam_start'];
    $tt_exam_end = $_POST['tt_exam_end'];

    /* Log Transaction */
    $sql = "UPDATE  exam_timetable SET tt_exam_date =?, tt_exam_start =?,  tt_exam_end =? WHERE tt_id = ?";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param(
        'ssss',
        $tt_exam_date,
        $tt_exam_start,
        $tt_exam_end,
        $tt_id
    );
    $prepare->execute();
    if ($prepare) {
        $success = "Exam Time Table Updated";
    } else {
        $err = "Failed!, Please Try Again";
    }
}

/* Delete Unit From Time Table */
if (isset($_POST['delete'])) {
    $tt_id = $_POST['tt_id'];

    /* Log Transaction */
    $sql = "DELETE FROM  exam_timetable  WHERE tt_id = ?";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param(
        's',
        $tt_id
    );
    $prepare->execute();
    if ($prepare) {
        $success = "Exam Time Table Deleted";
    } else {
        $err = "Failed!, Please Try Again";
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
                            <h1 class="m-0 text-bold">Exam Timetable</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active">Timetable</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Add Subject To Time Table</button>
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
                                                <div class="form-group col-md-6">
                                                    <label for="">Class</label>
                                                    <select class="form-control" name="tt_exam_class_id">
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
                                                <div class="form-group col-md-6">
                                                    <label for="">Subject</label>
                                                    <select class="form-control" name="tt_subject_id">
                                                        <?php
                                                        $ret = "SELECT * FROM subject ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($subject = $res->fetch_object()) {
                                                        ?>
                                                            <option value="<?php echo $subject->subject_id; ?>"><?php echo $subject->subject_code . ' - ' . $subject->subject_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="">Exam Date</label>
                                                    <input type="date" required name="tt_exam_date" class="form-control">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="">Start Time</label>
                                                    <input type="time" required name="tt_exam_start" class="form-control">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="">End Time</label>
                                                    <input type="time" required name="tt_exam_end" class="form-control">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="">Invigilator</label>
                                                    <select class="form-control" name="tt_exam_invigilator">
                                                        <?php
                                                        $ret = "SELECT * FROM teacher ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($subject = $res->fetch_object()) {
                                                        ?>
                                                            <option value="<?php echo $subject->t_id; ?>"><?php echo $subject->t_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" name="add_tt" class="btn btn-primary">Submit</button>
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
                                        <th>Subject Details</th>
                                        <th>Class Details</th>
                                        <th>Invigilator </th>
                                        <th>Exam Dates & Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM exam_timetable et
                                    INNER JOIN subject s ON s.subject_id = et.tt_subject_id
                                    INNER JOIN teacher t ON t.t_id = et.tt_exam_invigilator
                                    INNER JOIN class c ON c.class_id = et.tt_exam_class_id";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($tt = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td>
                                                Code: <?php echo $tt->subject_code; ?><br>
                                                Name: <?php echo $tt->subject_name; ?>
                                            </td>
                                            <td>
                                                Code: <?php echo $tt->class_code; ?><br>
                                                Name: <?php echo $tt->class_name; ?>
                                            </td>
                                            <td>
                                                <?php echo $tt->t_name; ?>
                                            </td>
                                            <td>
                                                Date: <?php echo date('d M Y', strtotime($tt->tt_exam_date)); ?><br>
                                                Start Time : <?php echo $tt->tt_exam_start; ?><br>
                                                End Time : <?php echo $tt->tt_exam_end; ?>
                                            </td>
                                            <td>
                                                <a class="badge badge-primary" data-toggle="modal" href="#edit-<?php echo $tt->tt_id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Update
                                                </a>
                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $tt->tt_id; ?>">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>
                                                <!-- Update Modal -->
                                                <div class="modal fade" id="edit-<?php echo $tt->tt_id; ?>">
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
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Exam Date</label>
                                                                                <input type="hidden" required name="tt_id" value="<?php echo $tt->tt_id; ?>" class="form-control">
                                                                                <input type="date" required name="tt_exam_date" value="<?php echo $tt->tt_exam_date; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Start Time</label>
                                                                                <input type="time" required name="tt_exam_start" value="<?php echo $tt->tt_exam_start; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">End Time</label>
                                                                                <input type="time" required name="tt_exam_end" value="<?php echo $tt->tt_exam_end; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <button type="submit" name="update_tt" class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="delete-<?php echo $tt->tt_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                    <h4>Delete Subject From Time Table ?</h4>
                                                                    <br>
                                                                    <p>Heads Up, You are about to delete <?php echo $tt->subject_name; ?> from exam time table. This action is irrevisble.</p>
                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                    <input type="hidden" name="tt_id" value="<?php echo $tt->tt_id; ?>">
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