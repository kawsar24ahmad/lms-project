<?php
include "header.php";
if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}
$statement = $pdo->prepare("select * from courses where id =? and instructor_id =?");
$statement->execute([
    $_REQUEST['id'],
    $_SESSION['instructor']['id']
]);
$total = $statement->rowCount();
if (!$total) {
    $_SESSION['error'] = "Course is not found!";
    header("location:" . BASE_URL . "instructor-courses");
    exit;
}


try {
    if (isset($_POST['form_module_add'])) {
        if (empty($_POST['name'])) {
            throw new Exception("Name field cannot be empty!");
        }
        if (empty($_POST['item_order'])) {
            throw new Exception("item_order field cannot be empty!");
        }
        if (!is_numeric($_POST['item_order'])) {
            throw new Exception("item_order field must be integer number!");
        }

        $statement = $pdo->prepare("insert into modules (name, course_id, total_video, total_resource, total_video_second, item_order) values (?,?,?,?,?,?)");
        $statement->execute([
            $_POST['name'],
            $_REQUEST['id'],
            0,
            0,
            0,
            $_POST['item_order']
        ]);


        $_SESSION['success'] = "Course module is created successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-curriculum/' . $_REQUEST['id']);
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-curriculum/' . $_REQUEST['id']);
    exit;
}
try {
    if (isset($_POST['form_module_edit'])) {
        if (empty($_POST['name'])) {
            throw new Exception("Name field cannot be empty!");
        }
        if (empty($_POST['item_order'])) {
            throw new Exception("item_order field cannot be empty!");
        }
        if (!is_numeric($_POST['item_order'])) {
            throw new Exception("item_order field must be integer number!");
        }

        $statement = $pdo->prepare("update modules set name=?, item_order=? where id=?");
        $statement->execute([
            $_POST['name'],
            $_POST['item_order'],
            $_POST['module_id']
        ]);


        $_SESSION['success'] = "Course module is updated successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-curriculum/' . $_POST['course_id']);
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-curriculum/' .  $_POST['course_id']);
    exit;
}
try {
    if (isset($_POST['form_module_delete'])) {


        $statement = $pdo->prepare("delete from modules where id=?");
        $statement->execute([
            $_POST['module_id']
        ]);
        $_SESSION['success'] = "Course module is deleted successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-curriculum/' . $_POST['course_id']);
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-curriculum/' .  $_POST['course_id']);
    exit;
}


?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit course</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit course</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    <?php include "instructor-sidebar.php" ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="mb-3">
                    <ul class="nav d-flex gap-3">
                        <li class="nav-item ">
                            <a href="<?= BASE_URL ?>instructor-course-edit-basic/<?= $_REQUEST['id'] ?>" class="nav-link  btn btn-primary  text-white">Basic information</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-photo/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Featured Photo</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-banner/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Featured Banner</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-video/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Featured Video</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-curriculum/<?= $_REQUEST['id'] ?>" class="active nav-link btn btn-primary  text-white">Curriculum</a>
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-top d-flex justify-content-between px-3 py-4">
                        <h3 class="card-title text-primary ">Modules</h3>
                        <a href="" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#addModule"><i class="fas fa-plus"></i> Add Module</a>
                        <!-- add Modal -->
                        <div class="modal fade" id="addModule" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Module</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <form action="" method="post">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label>Name</label>
                                                                <input type="text" class="form-control" name="name" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label> Order</label>
                                                                <input type="text" class="form-control" name="item_order" value="">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary" name="form_module_add">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="example1">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Total video</th>
                                        <th>Total resource</th>
                                        <th>Total hour</th>
                                        <th>Order</th>
                                        <th>Lesson</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $statement = $pdo->prepare("select * from modules  where course_id = ? order by item_order");
                                    $statement->execute([
                                        $_REQUEST['id']
                                    ]);
                                    if ($statement->rowCount() > 0) {
                                        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($data as $index => $row) { ?>
                                            <tr>
                                                <td><?= $index += 1 ?></td>
                                                <td><?= $row['name']; ?></td>
                                                <td><?= $row['total_video']; ?></td>
                                                <td><?= $row['total_resource']; ?></td>
                                                <td><?= convertSecondsToMinutesHours($row['total_video_second']); ?></td>
                                                <td><?= $row['item_order']; ?></td>
                                                
                                                <td>
                                                    <a href="<?= BASE_URL ?>instructor-course-edit-curriculum-lesson/<?= $_REQUEST['id'] . '/'.$row['id'] ?>" class="btn btn-success">Lesson</a>
                                                </td>

                                                <td class="pt_10 pb_10">
                                                    <a href="" class="btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editModule<?= $index ?>"><i class="fas fa-edit"></i></a>
                                                    <a href="" class="btn btn-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModule<?= $index ?>"><i class="fas fa-trash"></i></a>
                                                </td>

                                                <!-- edit Modal -->
                                                <div class="modal fade" id="editModule<?= $index ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Module</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="module_id" value="<?= $row['id'] ?>">
                                                                            <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group mb-3">
                                                                                        <label>Name</label>
                                                                                        <input type="text" class="form-control" name="name" value="<?= $row['name'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group mb-3">
                                                                                        <label> Order</label>
                                                                                        <input type="text" class="form-control" name="item_order" value="<?= $row['item_order'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary" name="form_module_edit">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- edit Modal -->
                                                <div class="modal fade" id="deleteModule<?= $index ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Module</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="module_id" value="<?= $row['id'] ?>">
                                                                            <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">

                                                                            <label class="mb-4" for="">Are you sure to delete?</label>

                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary" name="form_module_delete">Yes</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                        </div>

                        </tr>
                    <?php      }
                                        # code...
                                    } else { ?>

                    <tr>
                        <td colspan="6" class="text-danger">No Item!</td>
                    </tr>

                <?php    }

                ?>

                </tbody>
                </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $("#featured_video_type").on('change', function() {
            $("#youtube, #vimeo, #mp4").hide()
            var selectedValue = $(this).val();
            if (selectedValue == "youtube") {
                $("#youtube").show()
            } else if (selectedValue == "vimeo") {
                $("#vimeo").show()
            } else if (selectedValue == "mp4") {
                $("#mp4").show()
            }
        })
    })
</script>

<?php include "footer.php" ?>