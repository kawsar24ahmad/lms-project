<?php
include 'header.php';

unset($_SESSION['student']);
$_SESSION['success'] = "You have Logged out successfully";
header('location: '. BASE_URL. 'login');
exit;
?>
