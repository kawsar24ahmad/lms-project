<?php include "header.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_SESSION['student']) || isset($_SESSION['instructor']) ) {
    $_SESSION['error'] = "You are already logged in";
    header("location:" . BASE_URL . 'student-dashboard');
    exit;
}

// student form 
if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is unvalid!");
        }
        $statement = $pdo->prepare("select * from students where email = ? and status = ?");
        $statement->execute([
            $_POST['email'],
            1
        ]);
        $total = $statement->rowCount();
        if (!$total) {
            throw new Exception("Email is not found!");
        } else {
            $token = hash("sha256", time() . random_bytes(16));



            $statement = $pdo->prepare("update students set token =? where email = ?");
            $statement->execute([
                $token,
                $_POST['email'],
            ]);

            $email_message = "please click the following link in order to reset password : </br>";
            $email_message .= '<a href="' . BASE_URL . 'student-reset-password.php?email=' . $_POST['email'] . '&token=' . $token . '">Reset Password</a>';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = 2525;
            $mail->setFrom(SMTP_FORM);
            $mail->addAddress($_POST['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body = $email_message;
            $mail->send();
            $success_message = "Please check your email. Follow the instructions to reset your password";

            $_SESSION['success'] = $success_message;
            header('location:' . BASE_URL . 'forget-password');
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
    }
}

// form instructor 
if (isset($_POST['form_instructor'])) {
    try {
        if ($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is nots valid!");
        }
        $statement = $pdo->prepare("select * from instructors where email = ? and status = ?");
        $statement->execute([
            $_POST['email'],
            1
        ]);
        $total = $statement->rowCount();
        if (!$total) {
            throw new Exception("Email is not found!");
        } else {
            $token = hash("sha256", time() . random_bytes(16));

            $statement = $pdo->prepare("update instructors set token =? where email = ?");
            $statement->execute([
                $token,
                $_POST['email'],
            ]);

            $email_message = "please click the following link in order to reset password : </br>";
            $email_message .= '<a href="' . BASE_URL . 'instructor-reset-password.php?email=' . $_POST['email'] . '&token=' . $token . '">Reset Password</a>';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = 2525;
            $mail->setFrom(SMTP_FORM);
            $mail->addAddress($_POST['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body = $email_message;
            $mail->send();
            $success_message = "Please check your email. Follow the instructions to reset your password";

            $_SESSION['success'] = $success_message;
            header('location:' . BASE_URL . 'forget-password');
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
    }
}

?>
<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Forget Password</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Forget Password</li>
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
                                <div class="mb-3">
                                    <label for="" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary bg-website" name="form_submit">
                                        Submit
                                    </button>
                                    <a href="<?= BASE_URL ?>login" class="primary-color">Back to Login Page</a>
                                </div>
                            </div>
                        </form>
                        <!-- // form content -->
                    </div>

                </div>
                <div class="tab-pane fade" id="instructor" role="tabpanel" aria-labelledby="instructor-tab" tabindex="0">
                    <!-- form content -->
                    <form action="" method="post">
                        <div class="login-form">
                            <div class="mb-3">
                                <label for="" class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary bg-website" name="form_instructor">
                                    Submit
                                </button>
                                <a href="<?= BASE_URL ?>login" class="primary-color">Back to Login Page</a>
                            </div>
                        </div>
                    </form>
                    <!-- // form content -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>