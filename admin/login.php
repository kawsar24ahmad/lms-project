<?php include "./layouts/top.php";

if (isset($_SESSION['admin'])) {
    $_SESSION['error'] = "You are already logged in";
    header('location: '. ADMIN_URL. 'dashboard.php');
    exit;
}

if (isset($_POST['form_login'])) {
    try {
        if ($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is unvalid!");
        }
        if ($_POST['password'] == '') {
            throw new Exception("password can not be empty");
        }
        $q = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND role = ?");
        $q->execute([
            $_POST['email'],
            'admin'
        ]);
        $total = $q->rowCount();
        if (!$total) {
            throw new Exception("Email is not found");
        }else{
            $result = $q->fetch(PDO::FETCH_ASSOC);
            if (!password_verify($_POST['password'], $result['password'])) {
                throw new Exception("Password does not match");
            }
            $_SESSION['admin'] = $result;
            $_SESSION['success'] = "you have Logged in successfully";
            header('location: '. ADMIN_URL .'dashboard.php');
            exit;
        }
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
                        <h4 class="text-center">Admin Panel Login</h4>
                    </div>
                    
                    <div class="card-body card-body-auth">
                        <form novalidate method="POST" action="<?php
                    echo $_SERVER['PHP_SELF'];
                    ?>">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" value="" autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="form_login" class="btn btn-primary btn-lg w_100_p">
                                    Login
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
<?php include "./layouts/footer.php"; ?>