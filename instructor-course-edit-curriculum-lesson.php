<?php
include "header.php";


if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}



$statement = $pdo->prepare("select * from modules where id =? and course_id =?");
$statement->execute([
    $_REQUEST['module_id'],
    $_REQUEST['course_id'],
]);
$total = $statement->rowCount();
if (!$total) {
    $_SESSION['error'] = "Module is not found!";
    header("location:" . BASE_URL . "instructor-courses");
    exit;
}
$statement = $pdo->prepare("select * from courses where id =? and status =?");
 $statement->execute([$_REQUEST['course_id'], "In Review"]);
 $total = $statement->rowCount();
 if ($total) {
    header("location:" . BASE_URL. "instructor-courses");
    exit;
 }


try {
    if (isset($_POST['form_lesson_add'])) {
        if (empty($_POST['name'])) {
            throw new Exception("Name field cannot be empty!");
        }
        if (empty($_POST['lesson_type'])) {
            throw new Exception("lesson_type field cannot be empty!");
        }
        if ($_POST['lesson_type'] == 'video') {
            if (empty($_POST['video_type'])) {
                throw new Exception("video type field cannot be empty!");
            }
            if ($_POST['video_type'] == "youtube") {
                if ($_POST['video_content_youtube'] == "") {
                    throw new Exception("video content can\'t be empty");
                }
            }
            if ($_POST['video_type'] == "vimeo") {
                if (empty($_POST['video_content_vimeo'])) {
                    throw new Exception("vimeo video content can\'t be empty", 1);
                }
            }
            if ($_POST['video_type'] == "mp4") {
                $path_mp4 = $_FILES['video_content_mp4']['name'];
                $tmp_path_mp4 = $_FILES['video_content_mp4']['tmp_name'];
    
                if ($path_mp4 == "") {
                    throw new Exception("You must Upload a mp4 video");
                }
                $result = explode('.', $path_mp4);
                $extension= $result[1];
                $file_name_mp4 = "lesson_video_". time(). '.'. $extension;
                
                $finfo  = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp_path_mp4);
                if ($mime !=  "video/mp4" ) {
                    throw new Exception("please upload a valid video");
                }   
                
            }
            if (empty($_POST['duration_second'])) {
                throw new Exception("Duration can not be empty");
            }
        }else {
            $path = $_FILES['resource_content']['name'];
            $tmp_path = $_FILES['resource_content']['tmp_name'];

            if ($path == "") {
                throw new Exception("You must Upload a resource");
            }
            $result = explode('.', $path);
            $extension= $result[1];
            $filename = "lesson_resource_". time(). '.'. $extension;
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_path);
            
            // Allowed MIME types
            $allowedMimes = [
                'application/msword',                         // .doc
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'application/pdf',                            // .pdf
                'application/zip',                            // .zip
                'application/x-zip-compressed',               // .zip (alternative)
                'multipart/x-zip',                            // .zip (alternative)
                'application/x-compressed',                    // .zip (older zip types)
                'text/plain'
            ];
            
            if (!in_array($mime, $allowedMimes)) {
                throw new Exception("Please upload a valid file (doc, docx, pdf, txt or zip)");
            }
            
            finfo_close($finfo);
              
        }
      
        if (empty($_POST['item_order'])) {
            throw new Exception("item_order field cannot be empty!");
        }
        
        if (!is_numeric($_POST['item_order'])) {
            throw new Exception("item_order field must be integer number!");
        }
        if ($_POST['lesson_type'] == 'video') {
            if ($_POST['video_type'] == "youtube"){
              
                $video_content = $_POST['video_content_youtube'];
            }
            if ($_POST['video_type'] == "vimeo"){
                $video_content = $_POST['video_content_vimeo'];
            }
            if ($_POST['video_type'] == "mp4"){
                $video_content = $file_name_mp4;
                move_uploaded_file($tmp_path_mp4, "uploads/". $file_name_mp4);
            }
            $video_type = $_POST['video_type'];
            $resource_content = "";
            $duration_second = convertDurationToSecond($_POST['duration_second']);
            
        }else {
            $video_type = "";
            $video_content= "";
            $duration_second = "";
            $resource_content = $filename;
            move_uploaded_file($tmp_path, "uploads/". $filename);
        }

        $statement = $pdo->prepare("insert into lessons (course_id, module_id, name, lesson_type, video_type, video_content, duration_second, resource_content, is_preview, item_order) values (?,?,?,?,?,?,?,?,?,?)");
        $success =  $statement->execute([
            $_POST['course_id'],
            $_POST['module_id'],
            $_POST['name'],
            $_POST['lesson_type'],
            $video_type,
            $video_content,
            $duration_second,
            $resource_content,
            $_POST['is_preview'],
            $_POST['item_order']
        ]);

        if ($_POST['lesson_type'] == "video") {
            $statement = $pdo->prepare("select * from modules where id =?");
            $statement->execute([
                $_POST['module_id'],
            ]);

            $module_data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if ($module_data) {
                $total_video_second = $module_data[0]['total_video_second'] + $duration_second;
                $total_video = $module_data[0]['total_video'] + 1;
                $statement = $pdo->prepare("update modules set total_video_second=?, total_video= ? where id =?");
                $statement->execute([
                    $total_video_second,
                    $total_video,
                    $_POST['module_id'],
                ]);
            }
            
        }else{
            $total_resource = $module_data[0]['total_resource'] + 1;

            $statement = $pdo->prepare("update modules set  total_resource= ? where id =?");
            $statement->execute([
                $total_resource,
                $_POST['module_id'],
            ]);
        }
        if ($_POST['lesson_type'] == "video") {
            $statement = $pdo->prepare("select * from courses where id =?");
            $statement->execute([
                $_POST['course_id'],
            ]);

            $course_data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if ($course_data) {
                $total_video_second = $course_data[0]['total_video_second'] + $duration_second;
                $total_video = $course_data[0]['total_video'] + 1;
                $statement = $pdo->prepare("update courses set total_video_second=?, total_video= ? where id =?");
                $statement->execute([
                    $total_video_second,
                    $total_video,
                    $_POST['course_id'],
                ]);
            }
            
        }else{
            $total_resource = $course_data[0]['total_resource'] + 1;

            $statement = $pdo->prepare("update courses set  total_resource= ? where id =?");
            $statement->execute([
                $total_resource,
                $_POST['course_id'],
            ]);
        }
       

        $_SESSION['success'] = "Lesson is created successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-curriculum-lesson/' . $_REQUEST['course_id']. '/'. $_REQUEST['module_id']);
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-curriculum-lesson/' . $_REQUEST['course_id']. '/'. $_REQUEST['module_id']);
    exit;
}
try {
    if (isset($_POST['form_lesson_edit'])) {
        if (empty($_POST['name'])) {
            throw new Exception("Name field cannot be empty!");
        }
        if (empty($_POST['item_order'])) {
            throw new Exception("item_order field cannot be empty!");
        }
        if (!is_numeric($_POST['item_order'])) {
            throw new Exception("item_order field must be integer number!");
        }

        $statement = $pdo->prepare("update lessons set name=?, is_preview =?, item_order=? where id=?");
        $statement->execute([
            $_POST['name'],
            $_POST['is_preview'],
            $_POST['item_order'],
            $_POST['lesson_id']
        ]);


        $_SESSION['success'] = "Lesson is updated successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-curriculum-lesson/' . $_POST['course_id'] . '/'. $_POST['module_id']);
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-curriculum-lesson/' . $_POST['course_id'] . '/'. $_POST['module_id']);
    exit;
}
try {
    if (isset($_POST['form_lesson_delete'])) {
        $statement = $pdo->prepare("select * from lessons where id =?");
        $statement->execute([
            $_POST['lesson_id'],
        ]);
        $lesson_data = $statement->fetchAll(PDO::FETCH_ASSOC);


        $statement = $pdo->prepare("select * from modules where id =?");
            $statement->execute([
                $_POST['module_id']
            ]);
        $module_data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($lesson_data[0]['lesson_type'] == "video") {
            if ($lesson_data[0]['video_type'] == "mp4") {
                unlink("uploads/". $lesson_data[0]['video_content']);
            }
            
            $new_total_video = $module_data[0]['total_video'] - 1;
            $new_total_video_second = $module_data[0]['total_video_second'] - $lesson_data[0]['duration_second'];
            $statement = $pdo->prepare("update modules set total_video =?, total_video_second =? where id =?");
            $statement->execute([
                $new_total_video,
                $new_total_video_second,
                $_POST['module_id']
            ]);
    
        }else {
            unlink("uploads/". $lesson_data[0]['resource_content']);
           
            $new_total_resource = $module_data[0]['total_resource'] - 1;
            $statement = $pdo->prepare("update modules set total_resource =? where id =?");
            $statement->execute([
                $new_total_resource,
                $_POST['module_id']
            ]);
        }
        $statement = $pdo->prepare("select * from courses where id =?");
        $statement->execute([
            $_POST['course_id']
        ]);
        $course_data = $statement->fetchAll(PDO::FETCH_ASSOC);


        if ($lesson_data[0]['lesson_type'] == "video") {
            
            $new_total_video = $course_data[0]['total_video'] - 1;
            $new_total_video_second = $course_data[0]['total_video_second'] - $lesson_data[0]['duration_second'];
            $statement = $pdo->prepare("update courses set total_video =?, total_video_second =? where id =?");
            $statement->execute([
                $new_total_video,
                $new_total_video_second,
                $_POST['course_id']
            ]);
    
        }else {
            $new_total_resource = $course_data[0]['total_resource'] - 1;
            $statement = $pdo->prepare("update courses set total_resource =? where id =?");
            $statement->execute([
                $new_total_resource,
                $_POST['course_id']
            ]);
        }

        $statement = $pdo->prepare("delete from lessons where id=?");
        $statement->execute([
            $_POST['lesson_id']
        ]);
        $_SESSION['success'] = "Lesson is deleted successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-curriculum-lesson/' . $_POST['course_id'] . '/'. $_POST['module_id']);
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-curriculum-lesson/' . $_POST['course_id'] . '/'. $_POST['module_id']);
    exit;
}

$statement1 = $pdo->prepare("select * from modules where id =?");
$statement1->execute([
    $_REQUEST['module_id']
]);
$module_data = $statement1->fetchAll(PDO::FETCH_ASSOC);



?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit course Curriculum (lesson)</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit course Curriculum (lesson)</li>
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
                            <a href="<?= BASE_URL ?>instructor-course-edit-basic/<?= $_REQUEST['course_id'] ?>" class="nav-link  btn btn-primary  text-white">Basic information</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-photo/<?= $_REQUEST['course_id'] ?>" class="nav-link btn btn-primary  text-white">Featured Photo</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-banner/<?= $_REQUEST['course_id'] ?>" class="nav-link btn btn-primary  text-white">Featured Banner</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-video/<?= $_REQUEST['course_id'] ?>" class="nav-link btn btn-primary  text-white">Featured Video</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-curriculum/<?= $_REQUEST['course_id'] ?>" class="active nav-link btn btn-primary  text-white">Curriculum</a>
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-top d-flex justify-content-between px-3 py-4">
                        <h3 class="card-title text-primary ">Module: <?= $module_data[0]['name']; ?> </h3>
                        <a href="" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#addLesson"><i class="fas fa-plus"></i> Add Lesson</a>
                        <!-- add Modal -->
                        <div class="modal fade" id="addLesson" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Lesson</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="course_id" value="<?=$_REQUEST['course_id'];?>">
                                                    <input type="hidden" name="module_id" value="<?=$_REQUEST['module_id'];?>">

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
                                                                <label>Lesson Type</label>
                                                                <select name="lesson_type" id="lesson_type" class="form-select">
                                                                    <option value="">Select</option>
                                                                    <option value="video">video</option>
                                                                    <option value="resource">resource</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3" id="video" style="display: none;">
                                                        <label for=""> video type *</label>
                                                        <div class="">
                                                            <select id="video_type" name="video_type" class="form-select">
                                                                <option value="">Select</option>
                                                                <option value="youtube">Youtube</option>
                                                                <option value="vimeo">Vimeo</option>
                                                                <option value="mp4">MP4</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3" style="display: none;" id="youtube">
                                                        <label for=""> video content (Youtube) *</label>
                                                        <div class="form-group">
                                                            <input type="text" name="video_content_youtube" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3" style="display: none;" id="vimeo">
                                                        <label for=""> video content (Vimeo) *</label>
                                                        <div class="form-group">
                                                            <input type="text" name="video_content_vimeo" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3" style="display: none;" id="mp4">
                                                        <label for=""> video content (MP4) *</label>
                                                        <div class="form-group">
                                                            <input type="file" name="video_content_mp4">
                                                        </div>
                                                    </div>
                                                    <div class="row" id="duration" style="display: none;">
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label>Duration (second)</label>
                                                                <input type="text" class="form-control" name="duration_second" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3" style="display: none;" id="resource">
                                                        <label for="">Resource content*</label>
                                                        <div class="form-group">
                                                            <input type="file" name="resource_content">
                                                        </div>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label>Is preview</label>
                                                                <select class="form-select" name="is_preview" >
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
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
                                                        <button type="submit" class="btn btn-primary" name="form_lesson_add">Submit</button>
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
                                        <th>Lesson type</th>
                                        <th>Video type</th>
                                        <th>Is preview</th>
                                        <th>Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $statement = $pdo->prepare("select * from lessons  where course_id = ? and module_id =? order by item_order");
                                    $statement->execute([
                                        $_REQUEST['course_id'],
                                        $_REQUEST['module_id']
                                    ]);
                                    if ($statement->rowCount() > 0) {
                                        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($data as $index => $row) { ?>
                                            <tr>
                                                <td><?= $index += 1 ?></td>
                                                <td><?= $row['name']; ?></td>
                                                <td><?= $row['lesson_type']; ?></td>
                                                <td><?= $row['video_type']; ?></td>
                                                
                                                <td>
                                                    <?php if($row['is_preview'] == 0): ?>
                                                        <div class="badge bg-danger">
                                                            No
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="badge bg-success">
                                                            Yes
                                                        </div>
                                                    <?php endif;?>
                                                
                                                </td>
                                                <td><?= $row['item_order']; ?></td>
                                                
                                                <td class="pt_10 pb_10">
                                                    <a href="" class="btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editModule<?= $index ?>"><i class="fas fa-edit"></i></a>
                                                    <a href="" class="btn btn-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteLesson<?= $index ?>"><i class="fas fa-trash"></i></a>
                                                </td>

                                                <!-- edit Modal -->
                                                <div class="modal fade" id="editModule<?= $index ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Lesson</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="lesson_id" value="<?= $row['id'] ?>">
                                                                            <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
                                                                            <input type="hidden" name="module_id" value="<?= $row['module_id'] ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group mb-3">
                                                                                        <label>Name</label>
                                                                                        <input type="text" class="form-control" name="name" value="<?= $row['name'] ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row" >
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group mb-3">
                                                                                        <label>Is preview</label>
                                                                                        <select class="form-select" name="is_preview" >
                                                                                            <option value="0" <?= $row['is_preview'] == 0 ? "selected" : "" ?>>No</option>
                                                                                            <option value="1" <?= $row['is_preview'] == 1 ? "selected" : "" ?>>Yes</option>
                                                                                        </select>
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
                                                                                <button type="submit" class="btn btn-primary" name="form_lesson_edit">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- edit Modal -->
                                                <div class="modal fade" id="deleteLesson<?= $index ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Lesson</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="lesson_id" value="<?= $row['id'] ?>">
                                                                            <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
                                                                            <input type="hidden" name="module_id" value="<?= $row['module_id'] ?>">

                                                                            <label class="mb-4" for="">Are you sure to delete?</label>

                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary" name="form_lesson_delete">Yes</button>
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
        $("#lesson_type").on('change', function() {
            $("#video, #resource", "#duration").hide()
            var selectedValue = $(this).val();
            if (selectedValue == "video") {
                $("#video").show()
                $("#resource").hide()
                $("#duration").hide()


            } else if (selectedValue == "resource") {
                $("#resource").show()
                $("#video").hide()
                $("#duration").hide()
                $("#youtube").hide()
                $("#vimeo").hide()
                $("#mp4").hide()
            }
             else if (selectedValue == "") {
                $("#resource").hide()
                $("#video").hide()
                $("#duration").hide()
                $("#youtube").hide()
                $("#vimeo").hide()
                $("#mp4").hide()
            }
        })
        $("#video_type").on('change', function() {
            $("#youtube, #vimeo, #mp4").hide()
            var selectedValue = $(this).val();
            if (selectedValue == "youtube") {
                $("#youtube").show()
                $("#duration").show()

            } else if (selectedValue == "vimeo") {
                $("#vimeo").show()
                $("#duration").show()

            } else if (selectedValue == "mp4") {
                $("#mp4").show()
                $("#duration").show()

            }
            else if (selectedValue == "") {
                $("#youtube").hide()
                $("#vimeo").hide()
                $("#mp4").hide()
                $("#duration").hide()

            }
        })
    })
</script>

<?php include "footer.php" ?>