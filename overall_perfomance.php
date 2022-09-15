<?php

session_start();
require_once('config/config.php');/* Load Config File */
require_once('config/checklogin.php');/* Load Checklogin */
checklogin();/* Invoke Check Login, Prevent Watery Log Ins & Session Hijacking */
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
                            <h1 class="m-0 text-bold">Marks</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active">Overall Perfomance</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <table id="export-data-table" class="table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Details</th>
                                        <th>Subject Details </th>
                                        <th>Aggregate Marks</th>
                                        <th>Grade Attained</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM marks m
                                    INNER JOIN student s ON m.marks_student_id = s.student_id
                                    INNER JOIN subject sb ON m.marks_subject_id = sb.subject_id";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($marks = $res->fetch_object()) {
                                        /* Compute Grade */
                                        if ($marks->marks_aggregate >= 70 && $marks->marks_aggregate <= 100) {
                                            $grade = "A";
                                        } elseif ($marks->marks_aggregate >= 60 && $marks->marks_aggregate <= 69) {
                                            $grade = "B";
                                        } elseif ($marks->marks_aggregate >= 50 && $marks->marks_aggregate <= 59) {
                                            $grade = "C";
                                        } elseif ($marks->marks_aggregate >= 40 && $marks->marks_aggregate <= 49) {
                                            $grade = "D";
                                        } elseif ($marks->marks_aggregate >= 30 && $marks->marks_aggregate <= 39) {
                                            $grade = "E";
                                        } else {
                                            $grade = "F";
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $marks->student_admno . ' ' . $marks->student_name; ?></td>
                                            <td>
                                                <?php echo $marks->subject_code . ' ' . $marks->subject_name; ?></td>
                                            </td>
                                            <td>
                                                <?php echo $marks->marks_aggregate; ?>
                                            </td>
                                            <td>
                                                <?php echo $grade; ?>
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