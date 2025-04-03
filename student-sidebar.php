<ul class="list-group list-group-flush">
    <li class="list-group-item <?= ($current_page == 'student-dashboard.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>student-dashboard">Dashboard</a>
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
    <li class="list-group-item <?= ($current_page == 'student-profile.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>student-profile">Edit Profile</a>
    </li>
    <li class="list-group-item">
        <a href="<?= BASE_URL ?>student-logout">Logout</a>
    </li>
</ul>