<?php
/*
 * Created on Sat Dec 11 2021
 *
 *  Devlan - devlan.co.ke 
 *
 * hello@devlan.info
 *
 *
 * The Devlan End User License Agreement
 *
 * Copyright (c) 2021 Devlan
 *
 * 1. GRANT OF LICENSE
 * Devlan hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 * install and activate this system on two separated computers solely for your personal and non-commercial use,
 * unless you have purchased a commercial license from Devlan. Sharing this Software with other individuals, 
 * or allowing other individuals to view the contents of this Software, is in violation of this license.
 * You may not make the Software available on a network, or in any way provide the Software to multiple users
 * unless you have first purchased at least a multi-user license from Devlan.
 *
 * 2. COPYRIGHT 
 * The Software is owned by Devlan and protected by copyright law and international copyright treaties. 
 * You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 * 3. RESTRICTIONS ON USE
 * You may not, and you may not permit others to
 * (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 * (b) modify, distribute, or create derivative works of the Software;
 * (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 * otherwise exploit the Software.  
 *
 * 4. TERM
 * This License is effective until terminated. 
 * You may terminate it at any time by destroying the Software, together with all copies thereof.
 * This License will also terminate if you fail to comply with any term or condition of this Agreement.
 * Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 * 5. NO OTHER WARRANTIES. 
 * Devlan  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 * Devlan SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 * EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 * SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 * ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 * INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 * SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 * THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 * HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 * 6. SEVERABILITY
 * In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 * affect the validity of the remaining portions of this license.
 *
 * 7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN  OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 * CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 * USE OF THE SOFTWARE, EVEN IF DEVLAN HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 * IN NO EVENT WILL DEVLAN  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 * TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 */
session_start();
require_once('config/config.php');/* Load Config File */
require_once('config/checklogin.php');/* Load Checklogin */
checklogin();/* Invoke Check Login, Prevent Watery Log Ins & Session Hijacking */

/* Add Subject */
if (isset($_POST['add_subject'])) {
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];

    /* Log This Transaction */
    $sql = "INSERT INTO subject (subject_code, subject_name) VALUES(?,?)";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param('ss', $subject_code, $subject_name);
    $prepare->execute();
    if ($prepare) {
        $success = "$subject_name, Added";
    } else {
        $err = "Failed!, Please Try Again";
    }
}
/* Update Subject */
if (isset($_POST['update_subject'])) {
    $subject_id = $_POST['subject_id'];
    $subject_code = $_POST['subject_code'];
    $subject_name  = $_POST['subject_name'];

    /* Log This Transaction */
    $sql = "UPDATE subject SET subject_name =?, subject_code =? WHERE subject_id =?";
    $prepare  = $mysqli->prepare($sql);
    $bind = $prepare->bind_param('sss', $subject_name, $subject_code, $subject_id);
    $prepare->execute();
    if ($prepare) {
        $success = "$subject_name, Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Delete Subject */
if (isset($_POST['delete'])) {
    $subject_id = $_POST['subject_id'];

    /* Log This Transaction */
    $sql = "DELETE FROM subject WHERE subject_id = ?";
    $prepare = $mysqli->prepare($sql);
    $bind = $prepare->bind_param('s', $subject_id);
    $prepare->execute();
    if ($prepare) {
        $info = "Subject Deleted";
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
                            <h1 class="m-0 text-bold">Subjects</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active">Subjects</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Add Subjects</button>
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
                                                    <label for="">Subject Code</label>
                                                    <input type="text" required name="subject_code" class="form-control">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="">Subject Name</label>
                                                    <input type="text" required name="subject_name" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" name="add_subject" class="btn btn-primary">Submit</button>
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
                                        <th>Subject Code</th>
                                        <th>Subject Name </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM subject ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($subject = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $subject->subject_code; ?></td>
                                            <td><?php echo $subject->subject_name; ?></td>
                                            <td>
                                                <a class="badge badge-primary" data-toggle="modal" href="#edit-<?php echo $subject->subject_id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Update
                                                </a>
                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $subject->subject_id; ?>">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>
                                                <!-- Update Modal -->
                                                <div class="modal fade" id="edit-<?php echo $subject->subject_id; ?>">
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
                                                                                <label for=""> Subject Code</label>
                                                                                <input type="text" required value="<?php echo $subject->subject_code; ?>" name="subject_code" class="form-control">
                                                                                <input type="hidden" required value="<?php echo $subject->subject_id; ?>" name="subject_id" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-12">
                                                                                <label for="">Subject Name</label>
                                                                                <input type="text" required name="subject_name" value="<?php echo $subject->subject_name; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <button type="submit" name="update_subject" class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="delete-<?php echo $subject->subject_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                    <h4>Delete <?php echo $subject->subject_name; ?> ?</h4>
                                                                    <br>
                                                                    <p>Heads Up, You are about to delete <?php echo $subject->subject_name; ?>. This action is irrevisble.</p>
                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                    <input type="hidden" name="subject_id" value="<?php echo $subject->subject_id; ?>">
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