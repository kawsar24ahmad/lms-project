<?php
include 'header.php';

unset($_SESSION['instructor']);
$_SESSION['success'] = "You have Logged out successfully";
header('location: '. BASE_URL. 'login');
exit;
?>
