<?php
include "header.php";
if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
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
    header("location:" . BASE_URL . "instructor-courses");
    exit;
}
try {
    //  get data from lessons 
    $statement = $pdo->prepare("select * from lessons where course_id=?");
    $statement->execute([$_REQUEST['id']]);
    $lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($lessons as $lesson) {
        if ($lesson['lesson_type'] == "video") {
            if ($lesson['video_type'] == "mp4") {
                unlink("uploads/" . $lesson['video_content']);
            }
        } else {
            unlink("uploads/" . $lesson['resource_content']);
        }
    }
    // delete lessons 
    $statement = $pdo->prepare("delete from lessons where course_id =?");
    $statement->execute([$_REQUEST['id']]);

    // delete modules
    $statement = $pdo->prepare("delete from modules where course_id =?");
    $statement->execute([$_REQUEST['id']]);

    // get course data 
    $statement = $pdo->prepare("select * from courses where id =? and instructor_id =?");
    $statement->execute([
        $_REQUEST['id'],
        $_SESSION['instructor']['id']
    ]);
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    unlink("uploads/" . $data[0]['featured_photo']);
    unlink("uploads/" . $data[0]['featured_banner']);
    if ($data[0]['featured_video_type'] == "mp4") {
        unlink("uploads/" . $data[0]['featured_video_content']);
    }
    // delete course
    $statement = $pdo->prepare("delete from courses where id =?");
    $statement->execute([$_REQUEST['id']]);

    $_SESSION['success'] = "Course  is deleted successfully";
    header("location:" . BASE_URL . 'instructor-courses');
    exit;
    //code...
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . BASE_URL . 'instructor-courses');
    exit;
}
