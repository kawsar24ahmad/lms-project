<?php include "header.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}


try {
    if (isset($_POST['form_submit_for_rivew'])) {
        $statement = $pdo->prepare("select * from lessons where course_id=?");
        $statement->execute([$_POST['course_id']]);
        $total = $statement->rowCount();
        if ($total == 0) {
            $_SESSION['error'] = "This course\'s has not lessons. Please add lesson";
            header("location:" . BASE_URL . 'instructor-courses');
            exit;
        }else{
            $statement = $pdo->prepare("update courses set status =? where id =?");
            $statement->execute([
                "In Review", 
                $_POST['course_id']
            ]);

            $statement =$pdo->prepare("select * from admins where id =?");
            $statement->execute([1]);
            $admin = $statement->fetchAll(PDO::FETCH_ASSOC);
            $admin_email = $admin[0]['email'];


            $link = ADMIN_URL . 'dashboard.php';
            $email_message = 'One course is submitted for review. Please check it :';
            $email_message .= '<a href="' . $link . '">Click Here</a>';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = 2525;
            $mail->setFrom(SMTP_FORM);
            $mail->addAddress($admin_email);
            $mail->isHTML(true);
            $mail->Subject = 'Course is submitted for review';
            $mail->Body = $email_message;
            $mail->send();
            $success_message = 'Course is submitted for review to admin.';
            $_SESSION['success'] = $success_message;
            header("location:" . BASE_URL . 'instructor-courses');
            exit;
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-courses');
    exit;
}

?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>All Courses</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">All Courses</li>
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
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="example1">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Photo</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $statement = $pdo->prepare("select * from courses where instructor_id = ? order by id desc");
                                    $statement->execute([
                                        $_SESSION['instructor']['id']
                                    ]);

                                    if ($statement->rowCount() > 0) {

                                        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($data as $index => $row) { ?>


                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $row['title'] ?></td>
                                                <td><img src="<?= BASE_URL . 'uploads/' . $row['featured_photo'] ?>" alt="" class="w-150"></td>
                                                <td>$<?= $row['price'] ?></td>

                                                <td class="badge <?php echo ($row['status'] == 'pending' || $row['status'] == 'In Review') ? 'bg-danger' : 'bg-success' ?>"><?= $row['status'] ?></td>
                                                <td class="pt_10 pb_10">
                                                    <?php if($row['status'] != "In Review"): ?>
                                                    <a href="<?= BASE_URL ?>instructor-course-edit-basic/<?= $row['id'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="<?=BASE_URL?>/instructor-course-delete/<?=$row['id']?>" class="btn btn-danger" onClick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                                    <?php endif; ?>
                                                    <form action="" method="post" class="mt-2">
                                                        <input type="hidden" value="<?=$row['id'];?>" name="course_id">
                                                        <?php if($row['status'] == "pending"):?>
                                                        <button onclick="alert('Are You Sure?')" class="btn btn-primary btn-sm" type="submit" name="form_submit_for_rivew">Submit for Review</button>
                                                        <?php elseif($row['status'] == "In Review"):?>
                                                        <button class="btn btn-primary btn-sm" disabled type="submit" name="form_submit_for_rivew">Submitted for Review</button>
                                                        <?php endif;?>
                                                    </form>
                                                </td>

                                            </tr>
                                        <?php   }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6">No items</td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>