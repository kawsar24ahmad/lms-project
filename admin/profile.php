<?php include 'layouts/top.php';



if (!isset($_SESSION['admin'])) {
    $_SESSION['error'] ="Please Login first to access to dashboard";
    header('location: '.ADMIN_URL.'login.php');
    exit;
}

?>
try {
    if (isset($_POST['form_update'])) {
        if ($_POST['full_name'] == "") {
            throw new Exception("Name field cannot be empty!");
        }
        if ($_POST['email'] == "") {
            throw new Exception("Email field cannot be empty!");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is not valid!");
        }

        $statement = $pdo->prepare("UPDATE admins SET full_name=?, email =? WHERE id = ?");
        $statement->execute([
            $_POST['full_name'],
            $_POST['email'],
            $_SESSION['admin']['id']
        ]);

        $_SESSION['admin']['full_name'] = $_POST['full_name'];
        $_SESSION['admin']['email'] = $_POST['email'];

        if (!empty($_POST['new_password']) || !empty($_POST['retype_password'])) {
            if ($_POST['new_password'] !== $_POST['retype_password']) {
                throw new Exception("Passwords do not match");
            }
            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $statement = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $statement->execute([$password, $_SESSION['admin']['id']]);
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
                if (!empty($_SESSION['admin']['photo'])) {
                    unlink('../uploads/' . $_SESSION['admin']['photo']);
                }

                if (move_uploaded_file($tmp_path, "../uploads/" . $file_name)) {
                    $statement = $pdo->prepare("UPDATE admins SET photo = ? WHERE id = ?");
                    $statement->execute([$file_name, $_SESSION['admin']['id']]);
                    $_SESSION['admin']['photo'] = $file_name;
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
<div class="main-content">

    <section class="section">

        <div class="section-header">
            <h1>Edit Profile</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if (isset($error_message)) { ?>
                                <script>
                                    alert(" <?php echo $error_message; ?>")
                                </script>
                            <?php  }
                            ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if (empty($_SESSION['admin']['photo'])): ?>
                                            <img src="<?= BASE_URL ?>uploads/default.png" alt="" class="profile-photo w_100_p">
                                        <?php else: ?>
                                            <img src="<?= BASE_URL . 'uploads/' . $_SESSION['admin']['photo'] ?>" alt="" class="profile-photo w_100_p">

                                        <?php endif; ?>

                                        <input type="file" class="mt_10" name="photo">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <label class="form-label">Name *</label>
                                            <input type="text" class="form-control" name="full_name" value="<?= $_SESSION['admin']['full_name'] ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Email *</label>
                                            <input type="text" class="form-control" name="email" value="<?= $_SESSION['admin']['email'] ?>">
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="new_password">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Retype Password</label>
                                            <input type="password" class="form-control" name="retype_password">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label"></label>
                                            <button type="submit" name="form_update" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'layouts/footer.php'; ?>