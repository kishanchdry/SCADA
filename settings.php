<?php 
include_once 'includes/header.php';

if($user == 399){
    Redirect::to('dashboard.php');
}

$data = new User();

?>
 <div class="mb-4 p-1 text-info">
        <p><a href="#">Settings </a> >> </p>
</div>


<!-- Content Row -->
<div class="row">
    <!-- Pending Requests Card Example -->
    <div class="col-xl-12 col-md-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="add-location.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-map-marker text-light"></i>
                            </span>
                            <span class="text">Location</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="parameter.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-cogs text-light"></i>
                            </span>
                            <span class="text">Parameters</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="add-zones.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-cogs text-light"></i>
                            </span>
                            <span class="text">Zones</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="add-unit.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-circle text-light"></i>
                            </span>
                            <span class="text">	Add Units</span>
                        </a>     
                    </div>   
                    <div class="col-md-3">
                        <a href="#" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-edit text-light"></i>
                            </span>
                            <span class="text">	Change Password</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-cogs text-light"></i>
                            </span>
                            <span class="text">	Parameters Config</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="update.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-edit text-light"></i>
                            </span>
                            <span class="text">Update Unit</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage-report-data.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-cogs text-light"></i>
                            </span>
                            <span class="text">Manage Report Data</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage-data.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-cogs text-light"></i>
                            </span>
                            <span class="text">Manage Data</span>
                        </a>
                    </div>
                    <div class="col-md-3">    
                        <a href="#" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-info text-light"></i>
                            </span>
                            <span class="text">	Limit Master</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="panel-lock.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-lock text-light"></i>
                            </span>
                            <span class="text">	Panel lock</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="alarm.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-bell text-light"></i>
                            </span>
                            <span class="text">	Alarm</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="panel-lock-shedule.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-lock text-light"></i>
                            </span>
                            <span class="text">	Panel lock Shedule</span>
                        </a>
                    </div>
                   
                    <div class="col-md-4">
                        <a href="update-panel-timing.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-clock text-light"></i>
                            </span>
                            <span class="text">	Panel lock Shedule Timing Update</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="offlineUnits.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-unlink text-light"></i>
                            </span>
                            <span class="text">	Offline Units</span>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>





<!-- /.container-fluid -->
<?php include 'includes/footer.php'; ?>