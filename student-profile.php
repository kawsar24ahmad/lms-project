<?php include "header.php" ?>

        <div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Profile</h2>
                        <div class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                                <li class="breadcrumb-item active">Profile</li>
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
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item active">
                                    <a href="student-dashboard.php">Dashboard</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="student-enrolled-courses.php">Enrolled Courses</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="student-order.php">Orders</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="student-wishlist.php">Wishlist</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="student-message.php">Message</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="student-review.php">Reviews</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="student-profile.php">Edit Profile</a>
                                </li>
                                <li class="list-group-item">
                                    <a href="login.php">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Existing Photo</label>
                                    <div class="form-group">
                                        <img src="<?= BASE_URL; ?>uploads/user-photo.jpg" alt="" class="user-photo">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Change Photo</label>
                                    <div class="form-group">
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Full Name *</label>
                                    <div class="form-group">
                                        <input type="text" name="full_name" class="form-control" value="Morshedul Arefin">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Email Address *</label>
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control" value="arefin@example.com">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Phone *</label>
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control" value="122-333-4523">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Country *</label>
                                    <div class="form-group">
                                        <input type="text" name="country" class="form-control" value="Bangladesh">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Address *</label>
                                    <div class="form-group">
                                        <input type="text" name="address" class="form-control" value="23 Street, NRA">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">State *</label>
                                    <div class="form-group">
                                        <input type="text" name="state" class="form-control" value="Khulna">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">City *</label>
                                    <div class="form-group">
                                        <input type="text" name="city" class="form-control" value="Khulna">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Zip Code *</label>
                                    <div class="form-group">
                                        <input type="text" name="zip" class="form-control" value="9100">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Password</label>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Retype Password</label>
                                    <div class="form-group">
                                        <input type="password" name="retype_password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="form_update" type="submit" class="btn btn-primary" value="Update">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php" ?>