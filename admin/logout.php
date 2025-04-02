<?php
include 'layouts/top.php';

unset($_SESSION['admin']);
$_SESSION['success'] = "You have Logged out successfully";
header('location: '. ADMIN_URL. 'login.php');

?>
