<?php include "header.php";

try {
    if (isset($_POST['form_update'])) {
        if ($_POST['name'] == "") {
            throw new Exception("Name field cannot be empty!");
        }
        if ($_POST['email'] == "") {
            throw new Exception("Email field cannot be empty!");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is not valid!");
        }
        if ($_POST['designation'] == "") {
            throw new Exception("designation field cannot be empty!");
        }
        if ($_POST['phone'] == "") {
            throw new Exception("phone field cannot be empty!");
        }
        if ($_POST['address'] == "") {
            throw new Exception("address field cannot be empty!");
        }
        if ($_POST['country'] == "") {
            throw new Exception("country field cannot be empty!");
        }
        if ($_POST['state'] == "") {
            throw new Exception("state field cannot be empty!");
        }
        if ($_POST['zip_code'] == "") {
            throw new Exception("zip code field cannot be empty!");
        }
        if ($_POST['city'] == "") {
            throw new Exception("city field cannot be empty!");
        }

        $statement = $pdo->prepare("UPDATE instructors SET
                name=?, 
                email =?,
                designation =?,
                phone=?,
                address=?,
                country=?,
                zip_code=?,
                city=?,
                state=?,
                website=?,
                biography=?,
                facebook=?,
                twitter=?,
                instagram=?,
                youtube=?
                WHERE id = ?");
        $statement->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['designation'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['country'],
            $_POST['zip_code'],
            $_POST['city'],
            $_POST['state'],
            $_POST['website'],
            $_POST['biography'],
            $_POST['facebook'],
            $_POST['twitter'],
            $_POST['instagram'],
            $_POST['youtube'],
            $_SESSION['instructor']['id']
        ]);

        $_SESSION['instructor']['name'] = $_POST['name'];
        $_SESSION['instructor']['email'] = $_POST['email'];
        $_SESSION['instructor']['designation'] = $_POST['designation'];
        $_SESSION['instructor']['phone'] = $_POST['phone'];
        $_SESSION['instructor']['address'] = $_POST['address'];
        $_SESSION['instructor']['country'] = $_POST['country'];
        $_SESSION['instructor']['zip_code'] = $_POST['zip_code'];
        $_SESSION['instructor']['city'] = $_POST['city'];
        $_SESSION['instructor']['state'] = $_POST['state'];
        $_SESSION['instructor']['website'] = $_POST['website'];
        $_SESSION['instructor']['biography'] = $_POST['biography'];
        $_SESSION['instructor']['facebook'] = $_POST['facebook'];
        $_SESSION['instructor']['twitter'] = $_POST['twitter'];
        $_SESSION['instructor']['instagram'] = $_POST['instagram'];
        $_SESSION['instructor']['youtube'] = $_POST['youtube'];

        // update password 
        if (!empty($_POST['new_password']) || !empty($_POST['retype_password'])) {
            if ($_POST['new_password'] !== $_POST['retype_password']) {
                throw new Exception("Passwords do not match");
            }
            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $statement = $pdo->prepare("UPDATE instructors SET password = ? WHERE id = ?");
            $statement->execute([
                $password,
                $_SESSION['instructor']['id']
            ]);
        }

        // Upload photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $tmp_path = $_FILES['photo']['tmp_name'];

            if (empty($tmp_path) || !file_exists($tmp_path)) {
                throw new Exception("Temporary file path is empty or file does not exist!");
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $file_name = time() . '.' . $extension;

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if (!$finfo) {
                throw new Exception("Failed to initialize file info!");
            }

            $mime = finfo_file($finfo, $tmp_path);
            finfo_close($finfo);

            if ($mime === "image/png" || $mime === "image/jpeg") {
                if (!empty($_SESSION['instructor']['photo'])) {
                    unlink('uploads/' . $_SESSION['instructor']['photo']);
                }

                if (move_uploaded_file($tmp_path, "uploads/" . $file_name)) {
                    $statement = $pdo->prepare("UPDATE instructors SET photo = ? WHERE id = ?");
                    $statement->execute([$file_name, $_SESSION['instructor']['id']]);
                    $_SESSION['instructor']['photo'] = $file_name;
                } else {
                    throw new Exception("Failed to upload photo.");
                }
            } else {
                throw new Exception("Please upload a valid photo (JPEG or PNG).");
            }
        }
        $_SESSION['success'] = "profile updated successfully";
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
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
                                <input type="text" name="title" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">slug *</label>
                            <div class="form-group">
                                <input type="text" name="slug" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">price *</label>
                            <div class="form-group">
                                <input type="text" name="price" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Old price </label>
                            <div class="form-group">
                                <input type="text" name="old_price" class="form-control" value="">
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
                                       <option value="<?= $row['id'];?>"><?= $row['name'];?></option>
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
                                       <option value="<?= $row['id'];?>"><?= $row['name'];?></option>
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
                                       <option value="<?= $row['id'];?>"><?= $row['name'];?></option>
                                   <?php }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Featured Banner</label>
                            <div class="form-group">
                                <input type="file" name="featured_banner">
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
                            <label for="">Featured video content *</label>
                            <div class="form-group">
                                <input type="text" name="featured_video_content_youtube" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" style="display: none;" id="vimeo">
                            <label for="">Featured video content *</label>
                            <div class="form-group">
                                <input type="text" name="featured_video_content_vimeo" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" style="display: none;" id="mp4">
                            <label for="">Featured video content *</label>
                            <div class="form-group">
                                <input type="file" name="featured_video_content_mp4">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Description </label>
                            <div class="form-group">
                                <textarea class="form-control editor" name="description"></textarea>
                            </div>
                        </div>
                        <div class="col-mdt
                            <div class="form-group">
                                <input name="form_update" type="submit" class="btn btn-primary" value="Update">
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