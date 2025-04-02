<?php include "header.php";

if (isset($_SESSION['student'])) {
    $_SESSION['error'] = "You are already logged in";
    header("location:". BASE_URL.'student-dashboard');
    exit;
}
if (isset($_POST['form_student'])) {
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
        $q = $pdo->prepare("select * from students where email=? and status =?");
        $q->execute([
            $_POST['email'],
            1
        ]);
        $total = $q->rowCount();
        if (!$total) {
            throw new Exception("Email is not found");
        }else{
            $result = $q->fetch(PDO::FETCH_ASSOC);
            if (!password_verify($_POST['password'], $result['password'])) {
                throw new Exception("Password does not match");
            }
            $_SESSION['student'] = $result;
            $_SESSION['success'] = "you have Logged in successfully";
            header('location: '. BASE_URL .'student-dashboard');
            exit;
        }
    } catch(Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}


?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Login</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Login</li>
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

                <ul class="nav nav-pills mb-3 nav-login-register" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="student-tab" data-bs-toggle="pill" data-bs-target="#student" type="button" role="tab" aria-controls="student" aria-selected="true">Student</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="instructor-tab" data-bs-toggle="pill" data-bs-target="#instructor" type="button" role="tab" aria-controls="instructor" aria-selected="false">Instructor</button>
                    </li>
                </ul>
                <div class="tab-content tab-login-register" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab" tabindex="0">
                        <!-- form content -->
                        <form action="" method="post">
                            <div class="login-form">
                                <form action="<?= BASE_URL ?>student-dashboard" method="post">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Email Address</label>
                                        <input type="text" class="form-control" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary bg-website" name="form_student">
                                            Login
                                        </button>
                                        <a href="<?= BASE_URL?>forget-password" class="primary-color">Forget Password?</a>
                                    </div>
                                </form>
                                <div class="mb-3">
                                    <a href="<?= BASE_URL?>register" class="primary-color">Don't have an account? Create Account</a>
                                </div>
                            </div>
                        </form>
                        <!-- // form content -->
                    </div>
                    <div class="tab-pane fade" id="instructor" role="tabpanel" aria-labelledby="instructor-tab" tabindex="0">
                        <!-- form content -->
                        <div class="login-form">
                            <form action="instructor-dashboard.php" method="post">
                                <div class="mb-3">
                                    <label for="" class="form-label">Email Address</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary bg-website">
                                        Login
                                    </button>
                                    <a href="forget-password.php" class="primary-color">Forget Password?</a>
                                </div>
                            </form>
                            <div class="mb-3">
                                <a href="register.php" class="primary-color">Don't have an account? Create Account</a>
                            </div>
                        </div>
                        <!-- // form content -->
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>