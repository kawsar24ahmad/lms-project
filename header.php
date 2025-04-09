<?php
ob_start();
session_start();
include "config/config.php";
include "config/functions.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


$current_page = substr($_SERVER['SCRIPT_FILENAME'],strrpos($_SERVER['SCRIPT_FILENAME'],'/') + 1); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>EduZora</title>

        <link rel="icon" type="image/png" href="<?= BASE_URL; ?>uploads/favicon.png">

        <!-- All CSS -->
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/animate.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/magnific-popup.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/owl.carousel.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/select2-bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/all.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/meanmenu.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/spacing.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist-front/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>dist-front/css/iziToast.min.css">

        
        <!-- All Javascripts -->
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery-3.7.1.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/bootstrap.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery.magnific-popup.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/owl.carousel.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/wow.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/select2.full.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery.waypoints.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/moment.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/counterup.min.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/multi-countdown.js"></script>
        <script src="<?php echo BASE_URL; ?>dist-front/js/jquery.meanmenu.js"></script>
        <script src="<?php echo BASE_URL ?>dist-admin/js/iziToast.min.js"></script>
        <script src="<?php echo BASE_URL ?>dist-front/tinymce/tinymce.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 left-side">
                        <ul>
                            <li class="phone-text"><i class="fas fa-phone"></i> 111-222-3333</li>
                            <li class="email-text"><i class="fas fa-envelope"></i> contact@example.com</li>
                        </ul>
                    </div>
                    <div class="col-md-6 right-side">
                        <ul class="right">
                            <?php if(isset($_SESSION['student'])):?>
                                <li class="menu">
                                 <a href="<?=BASE_URL?>student-dashboard"><i class="fas fa-sign-in-alt"></i> Student Dashboard</a>
                                </li>
                            <?php elseif(isset($_SESSION['instructor'])):?>
                                <li class="menu">
                                 <a href="<?=BASE_URL?>student-dashboard"><i class="fas fa-sign-in-alt"></i> Instructor Dashboard</a>
                                </li>
                            <?php else:?>
                            <li class="menu">
                                <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                            </li>
                            <li class="menu">
                                <a href="<?= BASE_URL?>register"><i class="fas fa-user"></i> Sign Up</a>
                            </li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="navbar-area" id="stickymenu">
            <!-- Menu For Mobile Device -->
            <div class="mobile-nav">
                <a href="<?= BASE_URL; ?>" class="logo">
                    <img src="<?= BASE_URL; ?>uploads/logo.png" alt="">
                </a>
            </div>

            <!-- Menu For Desktop Device -->
            <div class="main-nav">
                <div class="container">
                    <nav class="navbar navbar-expand-md navbar-light">
                        <a class="navbar-brand" href="<?= BASE_URL; ?>">
                            <img src="<?= BASE_URL; ?>uploads/logo.png" alt="">
                        </a>
                        <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item <?php echo ($current_page == '<?= BASE_URL; ?>') ? 'active' : '' ?>">
                                    <a href="<?= BASE_URL; ?>" class="nav-link">Home</a>
                                </li>
                                <li class="nav-item <?php echo ($current_page == 'courses.php') ? 'active' : '' ?>">
                                    <a href="courses.php" class="nav-link">Courses</a>
                                </li>
                                <li class="nav-item <?php echo ($current_page == 'instructors.php') ? 'active' : '' ?>">
                                    <a href="instructors.php" class="nav-link">Instructors</a>
                                </li>
                                <li class="nav-item <?php echo ($current_page == 'blog.php') ? 'active' : '' ?>">
                                    <a href="blog.php" class="nav-link">Blog</a>
                                </li>
                                <li class="nav-item dropdown <?php echo ($current_page == 'about.php' || $current_page == 'privacy.php' || $current_page == 'terms.php'  ) ? 'active' : '' ?>">
                                    <a class="nav-link dropdown-toggle" href="javascript:void;" id="galleryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pages
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="galleryDropdown">
                                        <li><a class="dropdown-item" href="about">About Us</a></li>
                                        <li><a class="dropdown-item" href="privacy.php">Privacy Policy</a></li>
                                        <li><a class="dropdown-item" href="terms.php">Terms and Conditions</a></li>
                                        <li><a class="dropdown-item" href="faq.php">FAQ</a></li>
                                        <li><a class="dropdown-item" href="page.php">Custom Page 1</a></li>
                                        <li><a class="dropdown-item" href="page.php">Custom Page 2</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item <?php echo ($current_page == 'contact.php') ? 'active' : '' ?>">
                                    <a href="contact.php" class="nav-link">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                        <div class="right-side">
                            <div class="search">
                                <form action="" method="post">
                                    <div class="search-icon">
                                        <input name="search" type="text" placeholder="Search courses...">
                                        <button type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="cart">
                                <a href="cart.php">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                                <span class="number">0</span>
                            </div>
                            <div class="wishlist">
                                <a href="wishlist.php">
                                    <i class="far fa-heart"></i>
                                </a>
                                <span class="number">0</span>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

