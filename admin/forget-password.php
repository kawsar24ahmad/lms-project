<?php include "./layouts/top.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['admin'])) {
    header('location: ' . ADMIN_URL . 'dashboard.php');
}

if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is unvalid!");
        }

        $q = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND role = ?");
        $q->execute([
            $_POST['email'],
            'admin'
        ]);
        $total = $q->rowCount();
        if (!$total) {
            throw new Exception("Email is not found");
        } else {
            $token = time();

            $statement = $pdo->prepare("UPDATE admins SET token = ? WHERE email = ?");
            $statement->execute([
                $token, 
                $_POST['email']
            ]);


            $email_message = "please click the following link in order to reset password :";
            $email_message .= '<a href="' . ADMIN_URL . 'reset-password.php?email='.$_POST['email'].'&token='.$token.'">Reset Password</a>';
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                           //Send using SMTP
                $mail->Host       = SMTP_HOST;    //Set the SMTP server to send through
                $mail->SMTPAuth   = true; //Enable SMTP authentication
                $mail->Username   = SMTP_USERNAME;    //SMTP username
                $mail->Password   = SMTP_PASSWORD;   //SMTP password
                $mail->SMTPSecure = SMTP_ENCRYPTION;            //Enable implicit TLS encryption
                $mail->Port       = SMTP_PORT;       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom(SMTP_FORM);
                $mail->addAddress($_POST['email']);     //Add a recipient

                //Content
                $mail->isHTML(true);                //Set email format to HTML
                $mail->Subject = 'Reset Password';
                $mail->Body    = $email_message;
                $mail->send();
                $_SESSION['success'] = "Please check your email. Follow the instructions to reset your password";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } catch (Exception $e) {
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
                        <h4 class="text-center">Forget Password</h4>
                    </div>

                    <?php
                    if (isset($error_message)) { ?>
                        <script>
                            alert(" <?php echo $error_message; ?>")
                        </script>
                    <?php  }
                    ?>
                    <?php
                    if (isset($success_message)) { ?>
                        <script>
                            alert(" <?php echo $success_message; ?>")
                        </script>
                    <?php  }
                    ?>

                    <div class="card-body card-body-auth">
                        <form novalidate method="POST" action="<?php
                                                                echo $_SERVER['PHP_SELF'];
                                                                ?>">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" value="" autofocus>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="form_submit" class="btn btn-primary btn-lg w_100_p">
                                    Submit
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?= ADMIN_URL ?>login.php"">
                                        Go Back to login?
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