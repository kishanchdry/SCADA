<?php 
include_once 'includes/header.php';

$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="#">Reports </a> >> </p>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Pending Requests Card Example -->
    <div class="col-xl-12 col-md-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="communicating-unit.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-link text-light"></i>
                            </span>
                            <span class="text">	Communicating Units</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="non-communication-units.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-unlink text-light"></i>
                            </span>
                            <span class="text">	Non-Communicating Units</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="alarms.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-bell text-light"></i>
                            </span>
                            <span class="text">Alarms Report</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="daily-flow.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-calendar-check text-light"></i>
                            </span>
                            <span class="text">	Daily Flow</span>
                        </a>     
                    </div>   
                    <div class="col-md-3">
                        <a href="monthly-flow.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-calendar-check text-light"></i>
                            </span>
                            <span class="text"aLL >Monthly Flow Report</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="monthly-unit-report.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-calendar-check text-light"></i>
                            </span>
                            <span class="text">Unit Monthly Flow Report</span>
                        </a>
                    </div>
                    
                    <div class="col-md-3">
                        <a href="panel-offline.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-power-off text-light"></i>
                            </span>
                            <span class="text">	Panel Offline</span>
                        </a>
                    </div>
                    <?php if($user !=4){?>
                    <div class="col-md-3">
                        <a href="rtc-report.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-info text-light"></i>
                            </span>
                            <span class="text">	RTC Report</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="limit-report.php" class="btn btn-info btn-icon-split mr-2 mb-2">
                            <span class="icon text-white-50">
                                <i class="fa fa-info text-light"></i>
                            </span>
                            <span class="text">	Limit Test Report</span>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- /.container-fluid -->
<?php include 'includes/footer.php'; ?>