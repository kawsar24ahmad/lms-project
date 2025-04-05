<?php
 include "header.php";
 if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}
try {
    if (isset($_POST['form_submit'])) {
        if (empty($_POST['title'])) {
            throw new Exception("Title field cannot be empty!");
        }
        if (empty($_POST['slug'])) {
            throw new Exception("Slug field cannot be empty!");
        }
        
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $_POST['slug'])) {
            throw new Exception("Slug must contain only letters, numbers, hyphens, or underscores!");
        }
        
        // Check if slug already exists
        $statement = $pdo->prepare("SELECT * FROM courses WHERE slug = ?");
        $statement->execute([$_POST['slug']]);
        if ($statement->rowCount() > 0) {
            throw new Exception("This slug already exists!");
        }
        
        if (!isset($_POST['price']) || $_POST['price'] === "") {
            throw new Exception("Price field cannot be empty!");
        }
        if (!is_numeric($_POST['price']) || $_POST['price'] < 0 || $_POST['price'] > 1000) {
            throw new Exception("Price field must be a number between 0 and 1000!");
        }
        
        // Validate old price only if it's set
        if (!empty($_POST['old_price'])) {
            if (!is_numeric($_POST['old_price']) || $_POST['old_price'] < 0 || $_POST['old_price'] > 1000) {
                throw new Exception("Old price must be a number between 0 and 1000!");
            }
        }
        
        if (empty($_POST['description'])) {
            throw new Exception("Description field cannot be empty!");
        }
        if (empty($_POST['category_id'])) {
            throw new Exception("Category ID cannot be empty!");
        }
        if (empty($_POST['label_id'])) {
            throw new Exception("Label ID cannot be empty!");
        }
        if (empty($_POST['language_id'])) {
            throw new Exception("Language ID cannot be empty!");
        }

        // featured banner upload 

        $path = $_FILES['featured_banner']['name'];
        $tmp_path = $_FILES['featured_banner']['tmp_name'];

        if ($path == "") {
            throw new Exception("You must Upload a photo", 1);
        }else{
            $result = explode('.', $path);
            $extension= $result[1];
            $file_name = "course_featured_banner_". time(). '.'. $extension;
            
            $finfo  = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_path);
            if ($mime !=  "image/png" && $mime !=  "image/jpeg" && $mime != 'image/gif' ) {
                throw new Exception("please upload a valid image");
            }        
        }
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
        
        
        if (empty($_POST['featured_video_type'])) {
            throw new Exception("Please select featured video type!");
        }
        if ($_POST['featured_video_type'] == "youtube") {
            if (empty($_POST['featured_video_content_youtube'])) {
                throw new Exception("You must give youtube content!");
            }
        }
        elseif ($_POST['featured_video_type'] == "vimeo") {
            if (empty($_POST['featured_video_content_vimeo'])) {
                throw new Exception("You must give vimeo content!");
            }
        }
        else{
            $path_mp4 = $_FILES['featured_video_content_mp4']['name'];
            $tmp_path_mp4 = $_FILES['featured_video_content_mp4']['tmp_name'];

            if ($path_mp4 == "") {
                throw new Exception("You must Upload a mp4 video");
            }
            $result = explode('.', $path_mp4);
            $extension= $result[1];
            $file_name_mp4 = "course_featured_video_". time(). '.'. $extension;
            
            $finfo  = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_path_mp4);
            if ($mime !=  "video/mp4" ) {
                throw new Exception("please upload a valid video");
            }   
        }

        move_uploaded_file($tmp_path, "uploads/". $file_name);
        move_uploaded_file($tmp_path2, "uploads/". $file_name2);


        if ($_POST['featured_video_type'] == "youtube"){
            $featured_video_content = $_POST['featured_video_content_youtube'];
        }
        if ($_POST['featured_video_type'] == "vimeo"){
            $featured_video_content = $_POST['featured_video_content_vimeo'];
        }
        if ($_POST['featured_video_type'] == "mp4"){
            $featured_video_content = $file_name_mp4;
            move_uploaded_file($tmp_path_mp4, "uploads/". $file_name_mp4);
        }

        // sql

        $statement = $pdo->prepare("insert into courses (
            title,
            slug,
            description,
            price,
            old_price,
            category_id,
            label_id,
            language_id,
            instructor_id,
            total_student,
            total_rating,
            total_rating_score,
            avarage_rating,
            featured_banner,
            featured_photo,
            featured_video_type,
            featured_video_content,
            total_video_hours,
            total_videos,
            total_resources,
            status,
            updated_at
        ) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $statement->execute([
            $_POST['title'],
            $_POST['slug'],
            $_POST['description'],
            $_POST['price'],
            $_POST['old_price'],
            $_POST['category_id'],
            $_POST['label_id'],
            $_POST['language_id'],
            $_SESSION['instructor']['id'],
            0,
            0,
            0,
            0,
            $file_name,
            $file_name2,
            $_POST['featured_video_type'],
            $featured_video_content,
            0,
            0,
            0,
            "pending",
            date('Y-m-d H:i:s'),
        ]);

        
          
        $_SESSION['success'] = "Course is created successfully";
        header("location:". BASE_URL. 'instructor-course-create');
        exit;
    }
}
 catch (Exception $e) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['slug'] = $_POST['slug'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['old_price'] = $_POST['old_price'];
    $_SESSION['description'] = $_POST['description'];
    $_SESSION['category_id'] = $_POST['category_id'];
    $_SESSION['label_id'] = $_POST['label_id'];
    $_SESSION['language_id'] = $_POST['language_id'];

    $_SESSION['error'] = $e->getMessage();
    header("location:". BASE_URL. 'instructor-course-create');
    exit;
}


?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Create a course</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Create a course</li>
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
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title *</label>
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" value="<?php if(isset($_SESSION['title'])){echo $_SESSION['title']; unset($_SESSION['title']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">slug *</label>
                            <div class="form-group">
                                <input type="text" name="slug" class="form-control" value="<?php if(isset($_SESSION['slug'])){echo $_SESSION['slug']; unset($_SESSION['slug']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">price *</label>
                            <div class="form-group">
                                <input type="text" name="price" class="form-control" value="<?php if(isset($_SESSION['price'])){echo $_SESSION['price']; unset($_SESSION['price']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Old price </label>
                            <div class="form-group">
                                <input type="text" name="old_price" class="form-control" value="<?php if(isset($_SESSION['old_price'])){echo $_SESSION['old_price']; unset($_SESSION['old_price']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Select a category *</label>
                            <div class="form-group">
                                <select name="category_id" id="" class="form-control">
                                    <option value="" class="form-control">Select category</option>
                                    <?php
                                    $statement = $pdo->prepare("select * from categories order by name asc");
                                    $statement->execute();
                                    
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {?>
                                       <option value="<?= $row['id'];?>"  <?php if(isset($_SESSION['category_id'])){
                                        if ($row['id'] == $_SESSION['category_id']) {
                                            echo 'selected';
                                            unset($_SESSION['category_id']);
                                        }
                                       } ?>><?= $row['name'];?></option>
                                   <?php }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Select a label *</label>
                            <div class="form-group">
                                <select name="label_id" id="" class="form-control">
                                    <option value="" class="form-control">Select category</option>
                                    <?php
                                    $statement = $pdo->prepare("select * from labels order by name asc");
                                    $statement->execute();
                                    
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {?>
                                       <option value="<?= $row['id'];?>" <?php if(isset($_SESSION['label_id'])){
                                        if ($row['id'] == $_SESSION['label_id']) {
                                            echo 'selected';
                                            unset($_SESSION['label_id']);
                                        }
                                       } ?>><?= $row['name'];?></option>
                                   <?php }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Select a language *</label>
                            <div class="form-group">
                                <select name="language_id" id="" class="form-control">
                                    <option value="" class="form-control">Select category</option>
                                    <?php
                                    $statement = $pdo->prepare("select * from languages order by name asc");
                                    $statement->execute();
                                    
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {?>
                                       <option value="<?= $row['id'];?>" <?php if(isset($_SESSION['language_id'])){
                                        if ($row['id'] == $_SESSION['language_id']) {
                                            echo 'selected';
                                            unset($_SESSION['language_id']);
                                        }
                                       } ?>><?= $row['name'];?></option>
                                   <?php }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Description </label>
                            <div class="form-group">
                                <textarea class="form-control editor" name="description"><?php if(isset($_SESSION['description'])){echo $_SESSION['description']; unset($_SESSION['description']);} ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Featured Banner</label>
                            <div class="form-group">
                                <input type="file" name="featured_banner">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Featured Photo</label>
                            <div class="form-group">
                                <input type="file" name="featured_photo">
                            </div>
                        </div>

                        
                        <div class="col-md-6 mb-3">
                            <label for="">Featured video type *</label>
                            <div class="form-group">
                                <select  id="featured_video_type" name="featured_video_type" class="form-control">
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
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <input name="form_submit" type="submit" class="btn btn-primary" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#featured_video_type").on('change', function(){
            $("#youtube, #vimeo, #mp4").hide()
            var selectedValue = $(this).val();
            if (selectedValue == "youtube") {
                $("#youtube").show()
            }else if(selectedValue == "vimeo"){
                $("#vimeo").show()
            }else if(selectedValue == "mp4"){
                $("#mp4").show()
            }
        })
    })
</script>

<?php include "footer.php" ?>