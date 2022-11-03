<?php 
include_once 'includes/header.php';

if(Session::exists('LoggedIn')){
    echo    '<div class="row  mb-4">
                <div class="col-xl-12">
                    <div id="notification" class="card bg-success text-white shadow">
                        <div class="card-body">
                            Welcome :-
                            <div class="text-white-80 small">'. Session::flash('LoggedIn') .'</div>
                        </div>
                    </div>
                </div>
            </div>';
}
$data = new User();




?>

<style>
    .table-bordered td, .table-bordered th {
        border: 1px solid #e3e6f0;
        font-size: 11px !important;
    }
</style>

<!-- Content Row -->
<div class="row">
   <?php 
        $phases = $data->selectAll('sd_industrial_phase');
        $c=$phases->count();
        for($i=0; $i<$c;$i++){
   ?>
    <!-- Pending Requests Card Example -->
    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-info text-uppercase mb-1">
                            <a href="phase-all-zones.php?p=<?php echo $phases->results()[$i]->phase_id; ?>"><?php echo $phases->results()[$i]->phase_name; ?></a>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters align-items-center">
                <div class="col-auto mr-2">
                      <div class="text-xs mb-0 font-weight-bold text-dark text-info-800">
                        <a class="text-dark" href="phase-allzone-data.php?p=<?php echo $phases->results()[$i]->phase_id; ?>">All Zones</a>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>


<div class="row">
    <div class="col-xl-5 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-9">
                        <h6 class="m-0 font-weight-bold text-primary">Unit Detail </h6>
                    </div>
                    
                    <div class="col-xl-3">
                       
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart_div"></div>
                <div id="dd"></div>  
                    <?php  
                        $units = $data->selectJoinAll('SELECT 
                            u.user_location,
                            COUNT(u.user_id) AS all_units, 
                            SUM(CASE WHEN c.status = 0 OR c.status = 2 OR c.status = 3 THEN 1 ELSE 0 END) AS ncs,
                            SUM(CASE WHEN c.status = 1 THEN 1 ELSE 0 END) AS cs
                            from 
                                comm_status c
                            INNER JOIN 
                                sd_user u ON u.user_id = c.user_id    
                            GROUP BY 
                            u.user_location');
                        
                            $c=$units->count();
                    ?>   
                    <script>
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['', 'Total Units', 'Communicating', 'Non-Communicating'],
                            <?php 
                            
                                    for($i=0; $i<$c; $i++){
                                    echo "['Phase ". $units->results()[$i]->user_location."', ".$units->results()[$i]->all_units.', '.$units->results()[$i]->cs.', '.$units->results()[$i]->ncs."],";
                                }
                            ?>
                            ]);

                            var options = {

                                height: 300,
                                colors: ['#17a2b8', '#4bdb4b', '#d84343']
                            };

                            var chart = new google.charts.Bar(document.getElementById('chart_div'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));



                        }
                    </script>
            </div>
        </div>
    </div>
    <div class="col-xl-7 col-lg-7">
    <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-9">
                        <h6 class="m-0 font-weight-bold text-primary">Unit Detail </h6>
                    </div>
                    
                    <div class="col-xl-3">
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <tr class="bg-info text-light">
                            <th  style='width:15%;'>
                            Area
                            </th>
                            <td  style='width:55%;color:#FFF;padding:0;'>
                                <table class="table table-bordered table-sm " style="margin:0;" width="100%" cellspacing="0">
                                    <tr>
                                        <td style='width:15%;color:#FFF;font-weight:900;'>
                                            Zone
                                        </td>
                                        <td style='width:15%;color:#FFF;font-weight:900;'>
                                            Total Units
                                        </td>
                                        <td style='width:15%;color:#FFF;font-weight:900;'>
                                            Comm. Units
                                        </td>
                                        <td style='width:15%;color:#FFF;font-weight:900;'>
                                            Non-Comm. Units
                                        </td>
                                        <td style='width:20%;color:#FFF;font-weight:900;'>
                                            Zone Set Limit (KLD)
                                        </td>
                                        <td style='width:20%;color:#FFF;font-weight:900;'>
                                            Zone Flow (KLD)
                                        </td>
                                    </tr>  
                                </table>
                            </td>
                            <th  style='width:15%;'>
                                Set Limit (KLD) location

                            </th>
                            <th  style='width:15%;'>
                                Flow (KLD) Location

                            </th>
                        </tr>
                        
                        <?php

                        $selectPhase = $data->selectAll("sd_industrial_phase");

                        $p_count = $selectPhase->count();

                        for($pi = 0; $pi<$p_count;$pi++){

                        $phase_id = $pi+1;                       
                       
                        ?>
                        <tr>
                        <td  style='width:15%;'><?php echo 'Phase '.$phase_id;  ?></td>
                        <td style='padding:0;border:none;width:55%;'>
                            <table width="100%" cellspacing="0" style='border:none;'>
                                    <?php  
                                    /// loop for phase  /////

                                        $selectZone = $data->getData("sd_industrial_phase_zone", array('phase_id', '=', $phase_id));

                                        $z_count = $selectZone->count();

                                        for($zi = 0; $zi<$z_count;$zi++){

                                        $zone_id =$zi+1;

                                        ///// loop for Zone  /////   
                                        
                                        $units = $data->selectJoinAll(
                                            "SELECT count(user_id) as units FROM sd_user WHERE user_location = ".$phase_id." AND user_zone = ".$zone_id);
                                        $units = $units->results()[0]->units;
            
                                        $com_units=$data->selectJoinAll(
                                            "SELECT count(u.user_id) as comUnits FROM sd_user u LEFT JOIN comm_status c ON c.user_id = u.user_id WHERE c.status = 1 AND u.user_location = ".$phase_id." AND u.user_zone =".$zone_id);
                                        $com_units = $com_units->results()[0]->comUnits;
            
                                        $nonCom_units=$data->selectJoinAll(
                                            "SELECT count(u.user_id) as nonComunits FROM sd_user u LEFT JOIN comm_status c ON c.user_id = u.user_id WHERE c.status = 0 AND u.user_location = ".$phase_id." AND u.user_zone = ".$zone_id." OR c.status = 2 AND u.user_location = ".$phase_id." AND u.user_zone = ".$zone_id." OR c.status = 3 AND u.user_location = ".$phase_id." AND u.user_zone = ".$zone_id);
                                        $nonCom_units = $nonCom_units->results()[0]->nonComunits;
            
                                        $zonetotalLimit = $data->selectJoinAll(
                                            "SELECT sum(user_limit) as totalLimit FROM sd_user WHERE user_location = ".$phase_id." AND user_zone =". $zone_id);
                                        $zonetotalLimit = $zonetotalLimit->results()[0]->totalLimit;
            
                                        $zonetodayflow = $data->selectJoinAll(
                                            "SELECT SUM(sp.today_flow) as todayFlow FROM sd_live_panel_status sp LEFT JOIN sd_user u ON sp.user_id = u.user_id Where u.user_location = ".$phase_id." AND u.user_zone = ".$zone_id);
                                        $zonetodayflow = $zonetodayflow->results()[0]->todayFlow;
                                            
                                        
                                        
                                        
                                    ?>
                                        <tr>
                                            <td style='width:15%;'> Zone <?php echo  $zone_id; ?></td>
                                            <td style='width:15%;'> <?php echo  $units; ?></td>
                                            <td style='width:15%;'> <?php echo  $com_units; ?></td>
                                            <td style='width:15%;'> <?php echo  $nonCom_units; ?></td>
                                            <td style='width:20%;'> <?php echo  round($zonetotalLimit, 2); ?></td>
                                            <td style='width:20%;'> <?php echo  round($zonetodayflow, 2); ?></td>
                                        </tr>
                                    <?php 
                                            } 
                                    ?>
                            </table>                        
                        </td>
                        <?php 
                            $punits = $data->selectJoinAll(
                                "SELECT count(user_id) as units FROM sd_user WHERE user_location = ".$phase_id);
                            $punits = $punits->results()[0]->units;

                            $phasetotalLimit = $data->selectJoinAll(
                                "SELECT sum(user_limit) as totalLimit FROM sd_user WHERE user_location =". $phase_id);
                            $phasetotalLimit = $phasetotalLimit->results()[0]->totalLimit;


                            $phasetodaylimit = $data->selectJoinAll(
                                "SELECT SUM(user_today_limit) as todayLimit FROM sd_user Where user_location =".$phase_id);
                            $phasetodaylimit = $phasetodaylimit->results()[0]->todayLimit;
                                

                            $phasetodaylimit =  ($phasetotalLimit * ($phasetodaylimit/$punits)) /  100;

                            $phasetodayFlow = $data->selectJoinAll(
                                "SELECT SUM(l.today_flow) as todayFlow FROM sd_live_panel_status l LEFT JOIN sd_user s ON l.user_id = s.user_id  Where s.user_location =".$phase_id);
                            $phasetodayFlow = $phasetodayFlow->results()[0]->todayFlow;
                        
                        ?>
                        <td style='width:15%;'> <?php echo  round($phasetodaylimit, 2); ?></td>
                        <td style='width:15%;'> <?php echo  round($phasetodayFlow, 2); ?></td>
                        
                        </tr>
                        <?php
                        
                        }

                        ?> 
                        <tr class="bg-info text-light">
                        <td  style='width:15%;'>Totals</td>
                        <td style='padding:0;border:none;width:55%;'>
                            <table width="100%" cellspacing="0" style='border:none;'>                                   
                                <tr>
                                    <td style='width:15%;'><?php $totalZons = $data->selectAll('sd_industrial_phase_zone'); echo $totalZons->count(); ?> Zones </td>
                                    <td style='width:15%;'><?php $totalUnits = $data->selectAll('sd_user'); echo $totalUnits->count(); ?> Units </td>
                                    <td style='width:15%;'><?php $totalComm = $data->selectJoinAll('SELECT * FROM comm_status WHERE status = 1'); if($totalComm) { echo $totalComm->count(); } ?> Comm. </td>
                                    <td style='width:15%;'><?php $totalNonComm = $data->selectJoinAll('SELECT * FROM comm_status WHERE status = 0 OR status = 2'); echo $totalNonComm->count(); ?> Non Comm. </td>
                                    <td style='width:20%;'><?php $totalLimit = $data->selectJoinAll('SELECT sum(user_limit) as totalLimit FROM sd_user '); echo $totalLimit = round($totalLimit->results()[0]->totalLimit, 2); ?> Limit </td>
                                    <td style='width:20%;'><?php $totalFlow = $data->selectJoinAll('SELECT SUM(today_flow) as totalFlow FROM sd_live_panel_status'); echo $totalFlow = round($totalFlow->results()[0]->totalFlow, 2); ?> Flow </td>
                                </tr>
                            </table>                        
                        </td>
                        
                        <td style='width:15%;'> <?=$totalLimit?> Total Limit </td>
                        <td style='width:15%;'> <?=$totalFlow?> Total Flow </td>
                        
                        </tr>                         
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>










<!-- /.container-fluid -->
<?php 



include 'includes/footer.php'; 



?>
