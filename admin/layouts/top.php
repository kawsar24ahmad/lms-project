<?php
include '../config/config.php';
include "./layouts/header.php";
include './helper.php';

$current_page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);

if ($current_page != 'login.php' && $current_page != 'forget-password.php' && $current_page != 'reset-password.php'):

    include "./layouts/navbar.php";
    include "./layouts/sidebar.php";

endif;
