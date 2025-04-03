<?php include "header.php";

$statement = $pdo->prepare("SELECT * FROM instructors WHERE email = ? AND token = ?");
$statement->execute([
    $_REQUEST['email'],
    $_REQUEST['token']
]);
$total = $statement->rowCount();

if (!$total) {
    $_SESSION['error'] = "not authorized.";
    header("location: " . BASE_URL . 'login');
    exit;
}


if (isset($_POST['form_reset_password'])) {
    try {
        if ($_POST['password'] == '' || $_POST['confirm_password'] == '') {
            throw new Exception("passwords can not be empty");
        }
        if ($_POST['password'] !=  $_POST['confirm_password']) {
            throw new Exception("password does not match");
        }
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $statement = $pdo->prepare("UPDATE instructors SET token = ?, password = ? WHERE email = ? AND token = ?");
        $statement->execute([
            '',
            $password,
            $_REQUEST['email'],
            $_REQUEST['token'],
        ]);
        $_SESSION['success'] = "Reset password is successful";
        header("location: " . BASE_URL . 'login');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Reset Password</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Reset Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">

                
                <div class="tab-content tab-login-register" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab" tabindex="0">
                        <!-- form content -->
                        <form action="" method="post">
                            <div class="login-form">
                                
                                    <div class="mb-3">
                                        <label for="" class="form-label">New password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary bg-website" name="form_reset_password">
                                            Submit
                                        </button>
                                        
                                    </div>
                               
                            </div>
                        </form>
                        <!-- // form content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>