<ul class="list-group list-group-flush">
    <li class="list-group-item <?= ($current_page == 'instructor-dashboard.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>instructor-dashboard">Dashboard</a>
    </li>
    
    <li class="list-group-item <?= ($current_page == 'instructor-profile.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>instructor-profile">Edit Profile</a>
    </li>
    <li class="list-group-item <?= (
        $current_page == 'instructor-courses.php' || 
        $current_page ==  'instructor-course-edit-basic.php'  ||
        $current_page ==  'instructor-course-edit-featured-photo.php' ||
        $current_page ==  'instructor-course-edit-featured-banner.php' ||
        $current_page ==  'instructor-course-edit-featured-video.php' ||
        $current_page ==  'instructor-course-edit-curriculum.php'
    ) ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>instructor-courses">All Courses</a>
    </li>
    <li class="list-group-item <?= ($current_page == 'instructor-course-create.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>instructor-course-create"> Create Course</a>
    </li>
    <li class="list-group-item">
        <a href="<?= BASE_URL ?>instructor-logout">Logout</a>
    </li>
</ul>