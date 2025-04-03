<?php
include "header.php";

if(!isset($_REQUEST['email'])||!isset($_REQUEST['token'])) {
    header('location: '.BASE_URL);
}

$statement = $pdo->prepare("select * from instructors where email =? and token=?");
$statement->execute([
    $_REQUEST['email'],
    $_REQUEST['token']
]);
$total = $statement->rowCount();

if ($total) {
    $statement = $pdo->prepare("update instructors set token=?, status=? where email = ? and token = ?");
    $statement->execute([
        "",
        1,
        $_REQUEST['email'],
        $_REQUEST['token'],
    ]);
    $_SESSION['success'] = "Email verification is successful";
    header("location: ". BASE_URL. 'login');
    exit;
}else{
    $_SESSION['error'] = "Email verification is not successful";
    header("location: ". BASE_URL. 'register');
    exit;
}

?>