<?php include "header.php" ?>

        <div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Message</h2>
                        <div class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                                <li class="breadcrumb-item active">Message</li>
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
                    <div class="col-lg-5 col-md-12">
                        <h3>All Messages</h3>
                        <div class="message-item message-item-admin-border">
                            <div class="message-top">
                                <div class="left">
                                    <img src="<?= BASE_URL; ?>uploads/user-photo.jpg" alt="">
                                </div>
                                <div class="right">
                                    <h4>Morshedul Arefin</h4>
                                    <h5>Admin</h5>
                                    <div class="date-time">2024-08-20 09:33:22 AM</div>
                                </div>
                            </div>
                            <div class="message-bottom">
                                <p>Thank you for contacting. Sure, you can take it with you without any problem.</p>
                            </div>
                        </div>
                        
                        <div class="message-item">
                            <div class="message-top">
                                <div class="left">
                                    <img src="<?= BASE_URL; ?>uploads/team-1.jpg" alt="">
                                </div>
                                <div class="right">
                                    <h4>Smith Brent</h4>
                                    <h5>Client</h5>
                                    <div class="date-time">2024-08-20 08:12:43 AM</div>
                                </div>
                            </div>
                            <div class="message-bottom">
                                <p>I forgot to tell one thing. Can you please allow some toys for my son in this tour? It will be very much helpful if you allow.</p>
                            </div>
                        </div>

                        

                    </div>

                    <div class="col-lg-4 col-md-12">
                        <h3>Write a message</h3>
                        <form action="" method="post">
                            <div class="mb-2">
                                <textarea name="" class="form-control h-150" cols="30" rows="10" placeholder="Write your message here"></textarea>
                            </div>
                            <div class="mb-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php" ?>