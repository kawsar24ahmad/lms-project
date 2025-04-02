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

            <!-- <li class="nav-item dropdown active">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i><span>Dropdown Items</span></a>
                <ul class="dropdown-menu">
                    <li class="active"><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 1</a></li>
                    <li class=""><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 2</a></li>
                </ul>
            </li> -->

            <li class="<?= ($current_page == 'setting.php') ? 'active' : '' ?>"><a class="nav-link" href="<?= ADMIN_URL ?>setting.php"><i class="fas fa-hand-point-right"></i> <span>Setting</span></a></li>

            <li class="<?= ($current_page == 'form.php') ? 'active' : '' ?>"><a class="nav-link" href="<?= ADMIN_URL ?>form.php"><i class="fas fa-hand-point-right"></i> <span>Form</span></a></li>

            <li class="<?= ($current_page == 'table.php') ? 'active' : '' ?>"><a class="nav-link" href="<?= ADMIN_URL ?>table.php"><i class="fas fa-hand-point-right"></i> <span>Table</span></a></li>

            <li class="<?= ($current_page == 'invoice.php') ? 'active' : '' ?>"><a class="nav-link" href="<?= ADMIN_URL ?>invoice.php"><i class="fas fa-hand-point-right"></i> <span>Invoice</span></a></li>

        </ul>
    </aside>
</div>