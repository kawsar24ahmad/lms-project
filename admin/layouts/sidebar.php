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

            <li class="nav-item dropdown <?= (
                $current_page == 'category-view.php' || 
                $current_page == 'category-add.php' || 
                $current_page == 'category-edit.php' || 
                $current_page == 'label-view.php'  || 
                $current_page == 'label-add.php' || 
                $current_page == 'label-edit.php' ||
                $current_page == 'language-view.php'  || 
                $current_page == 'language-add.php' || 
                $current_page == 'language-edit.php' 
                ) ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Course Section</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ( 
                        $current_page == 'category-view.php' || 
                        $current_page == 'category-add.php' || 
                        $current_page == 'category-edit.php'
                    ) ? 'active' : '' ?>"><a class="nav-link" href="<?=ADMIN_URL?>category-view.php"><i class="fas fa-angle-right"></i> Category</a></li>
                    <li class="<?= (
                         $current_page == 'label-view.php'  || 
                         $current_page == 'label-add.php' || 
                         $current_page == 'label-edit.php' 
                    ) ? 'active' : '' ?>"><a class="nav-link" href="<?=ADMIN_URL?>label-view.php"><i class="fas fa-angle-right"></i> Label</a></li>
                    <li class="<?= (
                         $current_page == 'language-view.php'  || 
                         $current_page == 'language-add.php' || 
                         $current_page == 'language-edit.php' 
                    ) ? 'active' : '' ?>"><a class="nav-link" href="<?=ADMIN_URL?>language-view.php"><i class="fas fa-angle-right"></i> Language</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown <?= (
                $current_page == 'setting-commission.php'
                ) ? 'active' : '' ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i><span>Settings</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= ( 
                        $current_page == 'setting-commission.php' 
                    ) ? 'active' : '' ?>"><a class="nav-link" href="<?=ADMIN_URL?>setting-commission.php"><i class="fas fa-angle-right"></i> Sales Commission</a></li>
                    
                </ul>
            </li>
        </ul>
    </aside>
</div>