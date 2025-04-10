<?php
include "header.php";
if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}


$full_url = 'http://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
$explode_result = explode("instructor-course-edit-featured-video", $full_url);
$slashCount = substr_count($explode_result[1],"/");
if ($slashCount > 1) {
    header("location:" . BASE_URL . "instructor-courses");
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
$data = $statement->fetchAll(PDO::FETCH_ASSOC);


try {
    if (isset($_POST['form_submit'])) {


        if (empty($_POST['featured_video_type'])) {
            throw new Exception("Please select featured video type!");
        }
        if ($_POST['featured_video_type'] == "youtube") {
            if (empty($_POST['featured_video_content_youtube'])) {
                throw new Exception("You must give youtube content!");
            }
        } elseif ($_POST['featured_video_type'] == "vimeo") {
            if (empty($_POST['featured_video_content_vimeo'])) {
                throw new Exception("You must give vimeo content!");
            }
        } else {
            $path_mp4 = $_FILES['featured_video_content_mp4']['name'];
            $tmp_path_mp4 = $_FILES['featured_video_content_mp4']['tmp_name'];

            if ($path_mp4 == "") {
                throw new Exception("You must Upload a mp4 video");
            }
            $result = explode('.', $path_mp4);
            $extension = $result[1];
            $file_name_mp4 = "course_featured_video_" . time() . '.' . $extension;

            $finfo  = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_path_mp4);
            if ($mime !=  "video/mp4") {
                throw new Exception("please upload a valid video");
            }
        }


        if ($_POST['featured_video_type'] == "youtube") {
            $featured_video_content = $_POST['featured_video_content_youtube'];
        }
        if ($_POST['featured_video_type'] == "vimeo") {
            $featured_video_content = $_POST['featured_video_content_vimeo'];
        }
        if ($_POST['featured_video_type'] == "mp4") {
            $featured_video_content = $file_name_mp4;

            if ($_POST['current_featured_video_content'] != "") {
                unlink("uploads/" . $_POST['current_featured_video_content']);
            }

            move_uploaded_file($tmp_path_mp4, "uploads/" . $file_name_mp4);
        }

        // sql

        $statement = $pdo->prepare("update courses set
            featured_video_type =?,
            featured_video_content=?,
            updated_at=?
            where id =?
       ");

        $statement->execute([
            $_POST['featured_video_type'],
            $featured_video_content,
            date('Y-m-d H:i:s'),
            $_REQUEST['id']
        ]);

        $_SESSION['success'] = "Course featured video is updated successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-featured-video/' . $_REQUEST['id']);
        exit;
    }
} catch (Exception $e) {


    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-featured-video/' . $_REQUEST['id']);
    exit;
}


?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit course featured video</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit course featured video</li>
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
                <div class="mb-5">
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
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-video/<?= $_REQUEST['id'] ?>" class="active nav-link btn btn-primary  text-white">Featured Video</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-curriculum/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Curriculum</a>
                        </li>
                    </ul>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label for="">Existing Featured video type :</label>
                            <p class="my-4"><strong><?= $data[0]['featured_video_type'] ?></strong></p>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="">Existing Featured video</label>
                            <div class="form-group">
                                <?php if ($data[0]['featured_video_type'] == "youtube"): ?>
                                    <iframe src="https://www.youtube.com/embed/<?php echo $data[0]['featured_video_content'] ?>" title="YouTube video" allowfullscreen></iframe>
                                <?php elseif ($data[0]['featured_video_type'] == "vimeo"): ?>
                                    <iframe src="https://player.vimeo.com/video/<?php echo $data[0]['featured_video_content'] ?>" title="Vimeo video" allowfullscreen></iframe>
                                <?php elseif ($data[0]['featured_video_type'] == "mp4"): ?>
                                    <video class="w-400 h-200" controls>
                                        <source src="<?= BASE_URL ?>uploads/<?= $data[0]['featured_video_content'] ?>" type="video/mp4">
                                        
                                    </video>

                                <?php endif; ?>
                            </div>
                        </div>



                        <div class="col-md-6 mb-3">
                            <label for="">Change Featured video type *</label>
                            <div class="form-group">
                                <select id="featured_video_type" name="featured_video_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="youtube">Youtube</option>
                                    <option value="vimeo">Vimeo</option>
                                    <option value="mp4">MP4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" style="display: none;" id="youtube">
                            <label for="">Featured video content (Youtube) *</label>
                            <div class="form-group">
                                <input type="text" name="featured_video_content_youtube" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" style="display: none;" id="vimeo">
                            <label for="">Featured video content (Vimeo) *</label>
                            <div class="form-group">
                                <input type="text" name="featured_video_content_vimeo" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" style="display: none;" id="mp4">
                            <label for="">Featured video content (MP4) *</label>
                            <div class="form-group">
                                <input type="file" name="featured_video_content_mp4">
                            </div>
                        </div>
                        <input type="hidden" name="current_featured_video_content" value="<?= $data[0]['featured_video_type'] == "mp4" ? $data[0]['featured_video_content'] : "" ?>">


                        <div class="col-md-12">
                            <div class="form-group">
                                <input name="form_submit" type="submit" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </form>
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