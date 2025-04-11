<?php
 include "header.php";
 if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}

$full_url = 'http://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
$explode_result = explode("instructor-course-edit-featured-photo", $full_url);
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
    header("location:" . BASE_URL. "instructor-courses");
    exit;
 }

$data = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("select * from courses where id =? and status =?");
 $statement->execute([$_REQUEST['id'], "In Review"]);
 $total = $statement->rowCount();
 if ($total) {
    header("location:" . BASE_URL. "instructor-courses");
    exit;
 }

try {
    if (isset($_POST['form_submit'])) {
        
        // featured banner upload 

      
        // featured_photo upload 

        $path2 = $_FILES['featured_photo']['name'];
        $tmp_path2 = $_FILES['featured_photo']['tmp_name'];

        if ($path2 == "") {
            throw new Exception("You must Upload a photo", 1);
        }else{
            $result = explode('.', $path2);
            $extension2= $result[1];
            $file_name2 = "course_featured_photo_". time(). '.'. $extension2;
            
            $finfo  = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_path2);
            if ($mime !=  "image/png" && $mime !=  "image/jpeg" && $mime != 'image/gif' ) {
                throw new Exception("please upload a valid image");
            }        
        }
        if($_POST['current_featured_photo'] != ""){
            unlink("uploads/". $_POST['current_featured_photo']);
        }

        move_uploaded_file($tmp_path2, "uploads/". $file_name2);

        // sql

        $statement = $pdo->prepare("update courses set 
            featured_photo =?,
            updated_at =?
            where id =?
        ");

        $statement->execute([
            $file_name2,
            date('Y-m-d H:i:s'),
            $_REQUEST['id']
        ]);

        
          
        $_SESSION['success'] = "Course featured photo is updated successfully";
        header("location:". BASE_URL. 'instructor-course-edit-featured-photo/'. $_REQUEST['id']);
        exit;
    }
}
 catch (Exception $e) {
    
    $_SESSION['error'] = $e->getMessage();
    header("location:". BASE_URL. 'instructor-course-edit-featured-photo/'. $_REQUEST['id']);
    exit;
}


?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit course Featured photo</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit course Featured photo</li>
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
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-photo/<?= $_REQUEST['id'] ?>"  class="nav-link active btn btn-primary  text-white">Featured Photo</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-banner/<?= $_REQUEST['id'] ?>" class="nav-link btn btn-primary  text-white">Featured Banner</a>
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
                            <label for="">Existing Featured Photo</label>
                            <div class="form-group">
                                <img src="<?= BASE_URL?>uploads/<?= $data[0]['featured_photo'] ?>" alt="">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Change Featured Photo</label>
                            <div class="form-group">
                                <input type="hidden" name="current_featured_photo" value="<?= $data[0]['featured_photo'] ?>">
                                <input type="file" name="featured_photo">
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