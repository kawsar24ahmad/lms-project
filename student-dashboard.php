<?php include "header.php";
if (!isset($_SESSION['student'])) {
    $_SESSION['error'] = "Login first";
    header("location:". BASE_URL. 'login');
    exit;
}

?>

        <div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Dashboard</h2>
                        <div class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
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
                        <?php include "student-sidebar.php" ?>
                            
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <h3 class="mb_20">Hello, <?= strtoupper($_SESSION['student']['name']);?></h3>
                        <div class="row box-items">
                            <div class="col-md-4">
                                <div class="box1">
                                    <h4>3</h4>
                                    <p>Completed Orders</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box2">
                                    <h4>2</h4>
                                    <p>Pending Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php" ?>