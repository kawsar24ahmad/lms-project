<ul class="list-group list-group-flush">
    <li class="list-group-item <?= ($current_page == 'instructor-dashboard.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>instructor-dashboard">Dashboard</a>
    </li>
    
    <li class="list-group-item <?= ($current_page == 'instructor-profile.php') ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>instructor-profile">Edit Profile</a>
    </li>
    <li class="list-group-item">
        <a href="<?= BASE_URL ?>instructor-logout">Logout</a>
    </li>
</ul>