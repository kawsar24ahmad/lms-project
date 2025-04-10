<?php
 include "header.php";
 if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}

$full_url = 'http://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
$explode_result = explode("instructor-course-edit-basic", $full_url);
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
        $statement = $pdo->prepare("SELECT * FROM courses WHERE slug = ? and id != ?");
        $statement->execute([
            $_POST['slug'],
            $_REQUEST['id']
        ]);
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


        // sql

        $statement = $pdo->prepare("update courses set 
                title=?,
                slug=?,
                description=?,
                price=?,
                old_price=?,
                category_id=?,
                label_id=?,
                language_id=?,
                updated_at=?
                where id = ?
            ");

        $statement->execute([
            $_POST['title'],
            $_POST['slug'],
            $_POST['description'],
            $_POST['price'],
            $_POST['old_price'],
            $_POST['category_id'],
            $_POST['label_id'],
            $_POST['language_id'],
            date('Y-m-d H:i:s'),
            $_REQUEST['id']
        ]);

           
        $_SESSION['success'] = "Course basic info is updated successfully";
        header("location:". BASE_URL. 'instructor-course-edit-basic/'. $_REQUEST['id']);
        exit;
    }
}
 catch (Exception $e) {

    $_SESSION['error'] = $e->getMessage();
    header("location:". BASE_URL. 'instructor-course-edit-basic/'. $_REQUEST['id']);
    exit;
}



?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit course basic info</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit course  basic info</li>
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
                            <a href="<?= BASE_URL ?>instructor-course-edit-basic/<?= $_REQUEST['id'] ?>" class="nav-link active btn btn-primary  text-white">Basic information</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>instructor-course-edit-featured-photo/<?= $_REQUEST['id'] ?>"  class="nav-link btn btn-primary  text-white">Featured Photo</a>
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
                            <label for="">Title *</label>
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" value="<?php echo $data[0]['title'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">slug *</label>
                            <div class="form-group">
                                <input type="text" name="slug" class="form-control" value="<?php echo $data[0]['slug'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">price *</label>
                            <div class="form-group">
                                <input type="text" name="price" class="form-control" value="<?php echo $data[0]['price'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Old price </label>
                            <div class="form-group">
                                <input type="text" name="old_price" class="form-control" value="<?php echo $data[0]['old_price'] ?>">
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
                                       <option value="<?= $row['id'];?>"  <?php echo ($data[0]['category_id'] == $row['id']) ? "selected": "" ?> ><?= $row['name'];?></option>
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
                                       <option value="<?= $row['id'];?>"   <?php echo ($data[0]['label_id'] == $row['id']) ? "selected": "" ?> ><?= $row['name'];?></option>
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
                                       <option value="<?= $row['id'];?>"  <?php echo ($data[0]['language_id'] == $row['id']) ? "selected": "" ?>><?= $row['name'];?></option>
                                   <?php }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Description </label>
                            <div class="form-group">
                                <textarea class="form-control editor" name="description"><?php echo $data[0]['description'] ?></textarea>
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


<?php include "footer.php" ?>