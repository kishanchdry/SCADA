<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
            <img width="35px" class="img-profile rounded-circle" src="img/logo.jpg"> 
        </div>


    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if($page_title=='dashboard'){echo 'active';} ?>">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item <?php if($page_title=='trends'){echo 'active';} ?>">
        <a class="nav-link" href="#">
            <i class="	fas fa-chart-line fa-fw"></i>
            <span>Trends</span></a>
    </li>
    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if($page_title=='reports'){echo 'active';} ?>">
        <a class="nav-link" href="reports.php">
            <i class="fas fa-fw fa-file-excel"></i>
            <span>Reports</span></a>
    </li>
   <?php if($user != 399 ){?>
    <li class="nav-item <?php if($page_title=='settings'){echo 'active';} ?>">
        <a class="nav-link" href="settings.php">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Settings</span></a>
    </li>
    <?php } ?>
    
   

    <?php if($user != 399){?>
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-fw"></i> 
                <span>Logout</span>
            </a>
        </li>
    <?php }else{?>
        <li class="nav-item">
            <a class="nav-link" href="logout-superviser.php" >
                <i class="fas fa-sign-out-alt fa-fw"></i> 
                <span>Logout</span>
            </a>
        </li>    
    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>



</ul>
<!-- End of Sidebar -->