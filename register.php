<?php include "header.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['form_student'])) {
    try {
        if ($_POST['name'] == "") {
            throw new Exception("Name field can not be empty");
        }
        if ($_POST['email'] == "") {
            throw new Exception("email field can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) {
            throw new Exception("email in not valid");
        }
        if ($_POST['password'] == ""|| $_POST['confirm_password'] == "") {
            throw new Exception("passwords field can not be empty");
        }
        if ($_POST['password'] != $_POST['confirm_password']) {
            throw new Exception("passwords does not match");
        }
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = hash("sha256", time(). random_bytes(16));
        $statement = $pdo->prepare("insert into students (name, email, password, token, status) values (?,?,?,?,?)");
        $statement->execute([
            $_POST['name'],
            $_POST['email'],
            $password,
            $token,
            0
        ]);
        $link = BASE_URL.'registration-verify.php?email='.$_POST['email'].'&token='.$token;
        $email_message = 'Please click on this link to verify registration: <br>';
        $email_message .= '<a href="'.$link.'">Click Here</a>';

        $mail = new PHPMailer(true);
        try {
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
            $mail->Subject = 'Registration Verification Email';
            $mail->Body = $email_message;
            $mail->send();
            $success_message = 'Registration is completed. An email is sent to your email address. Please check that and verify the registration.';
            $_SESSION['success'] = $success_message;
            header('location:'. BASE_URL.'register');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
        header('location:'. BASE_URL.'register');
        exit;
    }
}




?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Create Account</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">Create Account</li>
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
                                    <label for="" class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Email Address *</label>
                                    <input type="text" class="form-control" name="email">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="" class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary bg-website" name="form_student">
                                        Create Account
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <a href="<?= BASE_URL ?>login" class="primary-color">Existing User? Login Now</a>
                                </div>
                            </div>
                        </form>
                        <!-- // form content -->
                    </div>
                    <div class="tab-pane fade" id="instructor" role="tabpanel" aria-labelledby="instructor-tab" tabindex="0">
                        <!-- form content -->
                        <div class="login-form">
                            <div class="mb-3">
                                <label for="" class="form-label">Name *</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Designation *</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email Address *</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Password *</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary bg-website">
                                    Create Account
                                </button>
                            </div>
                            <div class="mb-3">
                                <a href="login.php" class="primary-color">Existing User? Login Now</a>
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