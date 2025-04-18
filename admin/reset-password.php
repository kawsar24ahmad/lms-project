<?php include "layouts/top.php";

$statement = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND token = ?");
$statement->execute([
    $_REQUEST['email'],
    $_REQUEST['token']
]);
$total = $statement->rowCount();

if (!$total) {
    $_SESSION['error'] = "not authorized.";
    header("location: ". ADMIN_URL. 'login.php');
    exit;
}


if (isset($_POST['form_reset_password'])) {
    try {
        if ($_POST['password'] == '' || $_POST['retype_password'] == '') {
            throw new Exception("password can not be empty");
        }
        if ($_POST['password'] !=  $_POST['retype_password']) {
            throw new Exception("password does not match");
        }
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $statement = $pdo->prepare("UPDATE admins SET token = ?, password = ? WHERE email = ? AND token = ?");
        $statement->execute([
            '',
            $password,
            $_REQUEST['email'],
            $_REQUEST['token'],
        ]);
        $_SESSION['success']= "Reset password is successful";
        header("location: ". ADMIN_URL. 'login.php');
        exit;
    } catch(Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<section class="section">
    <div class="container container-login">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary border-box">
                    <div class="card-header card-header-auth">
                        <h4 class="text-center">Reset Password</h4>
                    </div>
                    
                    <div class="card-body card-body-auth">
                        <form novalidate method="POST" action="">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="retype_password" placeholder="Retype Password">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="form_reset_password" class="btn btn-primary btn-lg w_100_p">
                                    Submit
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?= ADMIN_URL ?>forget-password.php">
                                        Forget Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "layouts/footer.php"; ?>