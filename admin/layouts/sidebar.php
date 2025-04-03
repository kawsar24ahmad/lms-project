<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="dashboard.php">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="dashboard.php"></a>
        </div>

        <ul class="sidebar-menu">

            <li class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>"><a class="nav-link" href="dashboard.php"><i class="fas fa-hand-point-right"></i> <span>Dashboard</span></a></li>

            <li class="nav-item dropdown <?= ($current_page == 'category-view.php') ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Course Section</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ($current_page == 'category-view.php') ? 'active' : '' ?>"><a class="nav-link" href="<?=ADMIN_URL?>category-view.php"><i class="fas fa-angle-right"></i> Category</a></li>
                    <li class=""><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 2</a></li>
                </ul>
            </li>

            

        </ul>
    </aside>
</div>