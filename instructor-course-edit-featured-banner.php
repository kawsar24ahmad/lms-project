<?php
include "header.php";
if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}

$full_url = 'http://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
$explode_result = explode("instructor-course-edit-featured-banner", $full_url);
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

        // featured banner upload 

        $path = $_FILES['featured_banner']['name'];
        $tmp_path = $_FILES['featured_banner']['tmp_name'];

        if ($path == "") {
            throw new Exception("You must Upload a photo", 1);
        } else {
            $result = explode('.', $path);
            $extension = $result[1];
            $file_name = "course_featured_banner_" . time() . '.' . $extension;

            $finfo  = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_path);
            if ($mime !=  "image/png" && $mime !=  "image/jpeg" && $mime != 'image/gif') {
                throw new Exception("please upload a valid image");
            }
        }


        if ($_POST['current_featured_banner'] != "") {
            unlink("uploads/" . $_POST['current_featured_banner']);
        }

        move_uploaded_file($tmp_path, "uploads/" . $file_name);

        // sql

        $statement = $pdo->prepare("update courses set 
            featured_banner =?,
            updated_at =?
            where id =?
        ");

        $statement->execute([
            $file_name,
            date('Y-m-d H:i:s'),
            $_REQUEST['id']
        ]);



        $_SESSION['success'] = "Course featured banner is updated successfully";
        header("location:" . BASE_URL . 'instructor-course-edit-featured-banner/' . $_REQUEST['id']);
        exit;
    }
} catch (Exception $e) {

    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-course-edit-featured-banner/' . $_REQUEST['id']);
    exit;
}


?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit course featured banner</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit course featured banner</li>
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
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-banner/<?= $_REQUEST['id'] ?>" class="active nav-link btn btn-primary  text-white">Featured Banner</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-video/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Featured Video</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-curriculum/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Curriculum</a>
                        </li>
                    </ul>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="">Existing Featured banner</label>
                            <div class="form-group">
                                <img src="<?= BASE_URL ?>uploads/<?= $data[0]['featured_banner'] ?>" alt="">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Change Featured banner</label>
                            <div class="form-group">
                                <input type="hidden" name="current_featured_banner" value="<?= $data[0]['featured_banner'] ?>">
                                <input type="file" name="featured_banner">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input name="form_submit" type="submit" value="Update" class="btn btn-primary" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>