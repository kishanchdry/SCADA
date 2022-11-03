<?php 
include_once 'includes/header.php';

$data = new User();

 $p = $_GET['p'];

?>

<div class="mb-4 p-1 text-info">
    <p>
        <a href="dashboard.php">Dashboard </a> >> 
        <a href="phase-all-zones.php?p=<?php echo $p; ?>">Phase <?php echo $p; ?> </a> >> 
        <a href="#">All Zones </a>
    </p>
</div>

<!-- Content Row -->
<div class="row">
   <?php 
        $zones = $data->getData('sd_industrial_phase_zone', array('phase_id', '=', $p ));
        $c=$zones->count();
        for($i=0; $i<$c;$i++){
   ?>
    <!-- Pending Requests Card Example -->
    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-info text-uppercase mb-1">
                            <a href="zone-data.php?z=<?php echo $zones->results()[$i]->zone_id; ?>&p=<?php echo $p ?>">Zone <?php echo $i+1; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>



<!-- /.container-fluid -->
<?php include 'includes/footer.php'; ?>
