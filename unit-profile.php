<?php 
include_once 'includes/header.php';
$u = $_GET['u'];
$data = new User();
$query ="SELECT user_location, user_address, `user_name`, user_zone, user_limit, user_today_limit, panel_unlock_timing, emergency_stop, door_limit_switch, overload, pump_status, tank_level,kld_limit_send, rtc_dd, rtc_hh, rtc_mm, auto_manual, panel_lock,today_flow,totalizer,c_flow FROM sd_user, sd_live_panel_status WHERE sd_user.user_id = ".$u." AND sd_live_panel_status.user_id =".$u;


$unit = $data->selectJoinAll($query);
$unit=$unit->results()[0]; 


?>
<div class="mb-4 p-1">
        <p>
            <a href="dashboard.php">Dashboard </a> >> 
            <a href="phase-all-zones.php?p=<?php echo $unit->user_location; ?>">Phase <?php echo $unit->user_location; ?> </a> >> 
            <a href="zone-data.php?z=<?php echo $unit->user_zone; ?>&p=<?php echo $unit->user_location; ?>"> Zone <?php echo $unit->user_zone; ?> </a> >> 
            <a href="#"><?php echo $unit->user_name; ?> </a></p>
</div>


<!-- Content Row -->
<div class="row">
  
    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-3 mb-4">
        <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-xl-12">
                            <h6 class="m-0 font-weight-bold text-primary">Unit Detail </h6>
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-md-6">
                        <strong>Unit Name</strong>
                    </div>
                    <div class="col-md-6">
                        <?php echo $unit->user_name; ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-6">
                        <strong>Address</strong>
                    </div>
                    <div class="col-md-6">
                    <?php echo $unit->user_address; ?>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-md-6">
                        <strong>Flow Limit</strong>
                    </div>
                    <div class="col-md-6">
                    <?php echo $unit->user_limit; ?>
                    </div>
                </div>
                <div class="row  mb-1">
                    <div class="col-md-6">
                        <strong>Shedular</strong>
                    </div>
                    <div class="col-md-6">
                    <?php echo $unit->panel_unlock_timing; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-5 col-md-5 mb-4">
        <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-xl-12">
                            <h6 class="m-0 font-weight-bold text-primary">Parameters</h6>
                        </div>
                      
                    </div>
                </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-md-3">
                        <strong>Today Flow</strong>
                    </div>
                    <div class="col-md-3">
                       <?php echo $unit->today_flow; ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Totalizer</strong>
                    </div>
                    <div class="col-md-3">
                    <?php echo $unit->totalizer; ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-3">
                        <strong>RVC Limit</strong>
                    </div>
                    <div class="col-md-3">
                    <?php echo $unit->kld_limit_send; ?>
                    </div>
                
                    <div class="col-md-3">
                        <strong> RTC Limit</strong>
                    </div>
                    <div class="col-md-3">
                    <?php echo $unit->rtc_dd.'-'. $unit->rtc_hh.'-'. $unit->rtc_mm; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
   
    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-xl-12">
                            <h6 class="m-0 font-weight-bold text-primary">Flow Meter </h6>
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-md-12">
                        <div id="chartdiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
  
    <!-- Pending Requests Card Example -->
    <div class="col-xl-8 col-md-8 mb-4">
        <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-xl-12">
                            <h6 class="m-0 font-weight-bold text-primary">Status </h6>
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-md-3 text-center">
                        <strong>Emargency Stop</strong>
                        </br></br>
                        <button class="btn <?php if($unit->emergency_stop == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->emergency_stop; ?>" id="emargency_stop"><?php if($unit->emergency_stop == 1){ echo 'ON';}else{ echo 'OFF';} ?></button>    
                    </div>
                    <div class="col-md-3 text-center">
                        <strong>Door Limit Switch</strong>
                        </br></br>
                        <button class="btn <?php if($unit->door_limit_switch == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->door_limit_switch; ?>" id="door_limit_switch"><?php if($unit->door_limit_switch == 1){ echo 'OPEN';}else{ echo 'CLOSE';} ?></button>       
                    </div>
                    <div class="col-md-3 text-center">
                        <strong>Overload</strong>
                        </br></br>
                        <button class="btn <?php if($unit->overload == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->overload; ?>" id="overload"><?php if($unit->overload == 1){ echo 'ON';}else{ echo 'OFF';} ?></button>      
                    </div>
                    <div class="col-md-3 text-center">
                        <strong>Pump</strong>
                        </br></br>
                        <button class="btn <?php if($unit->pump_status == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->pump_status; ?>" id="pump_status"><?php if($unit->pump_status == 1){ echo 'ON';}else{ echo 'OFF';} ?></button> 
                    </div>
                    <div class="col-md-3 text-center">
                        <strong>Level</strong>
                        </br></br>
                        <button class="btn <?php if($unit->tank_level == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->tank_level; ?>" id="tank_level"><?php if($unit->tank_level == 1){ echo 'FULL';}else{ echo 'EMPTY';} ?></button>    
                    </div>
                    <div class="col-md-3 text-center">
                        <strong>Auto/Manual</strong>
                        </br></br>
                        <button class="btn <?php if($unit->auto_manual == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->auto_manual; ?>" id="auto_manual"><?php if($unit->auto_manual == 1){ echo 'AUTO';}else{ echo 'MANUAL';} ?></button>    
                    </div>
                    <div class="col-md-3 text-center">
                        <strong>Panel Lock/Unlock</strong>
                        </br></br>
                        <button class="btn <?php if($unit->panel_lock == 1){ echo 'btn-danger';}else{ echo 'btn-success';} ?> mb-4" value="<?php echo $unit->panel_lock; ?>" id="panel_lock"><?php if($unit->panel_lock == 1){ echo 'LOCK';}else{ echo 'UN-LOCK';} ?></button>    
                    </div>
                    
                </div>
            </div>
        </div>


        <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info ">
                    <div class="row">
                        <div class="col-xl-12 ">
                            <h6 class="m-0 font-weight-bold text-light">Alarm Details </h6>
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>
                                    Alarm
                                </th>
                                <th>
                                    Start at
                                </th>
                                <th>
                                    End at
                                </th>
                                <th>
                                    
                                </th>
                                <th>
                                   Accknowledge
                                </th>
                                
                            </tr>
                        </thead>

                        <tbody id="alarm">
                       
                           
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-4">
        <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info">
                    <div class="row">
                        <div class="col-xl-12 bg-info">
                            <h6 class="m-0 font-weight-bold text-light">Monthly Flow Details </h6>
                        </div>
                    </div>
                </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable1" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th id='short'>
                                    Date
                                </th>
                                <th>
                                    Flow (KLD)
                                </th>
                                                              
                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                       
                       $yesterdayflow = $data->selectJoinAll('SELECT * FROM end_totalizer WHERE tdate >= DATE_ADD(CURDATE(),INTERVAL - 1 DAY) AND id = '.$u);
                       if($yesterdayflow){
                           $yesterdayflow=$yesterdayflow->results()[0]->e_totalizer;
                       }

                       $currentflow = $data->selectJoinAll("SELECT * FROM  sd_live_panel_status WHERE date(date_time) >= DATE_ADD(CURDATE(),INTERVAL - 1 DAY) AND user_id =".$u);
                       

                       if($currentflow){
                           $currentflow = $currentflow->results()[0]->totalizer;
                      
               
                        ?>
                        <tr class="gridRow_Dashboard">
                            <td align="left" class="leftPadding10">
                                <span id=""><?php echo date('Y-m-d'); ?></span>
                            </td>
                            <td class="rightPadding10" align="left">
                                <span
                                    id=""><?php echo round($currentflow-$yesterdayflow, 2); ?></span>
                            </td>
                        </tr>
                        

                        <?php
                       }
                          
                           $monthelyflow = $data->selectJoinAll("SELECT * FROM  tester WHERE tdate >= DATE_ADD(CURDATE(),INTERVAL - 31 DAY) AND id = ".$u." ORDER BY tdate DESC");
                           $c=$monthelyflow->count();
                           for($i=0;$i<$c;$i++){
                   
                        ?>
                        <tr class="gridRow_Dashboard">
                            <td align="left" class="leftPadding10">
                                <span id=""><?php echo $monthelyflow->results()[$i]->tdate; ?></span>
                            </td>
                            <td class="rightPadding10" align="left">
                                <span
                                    id=""><?php if($monthelyflow->results()[$i]->totalizer_diff==1.00){ echo '-';}else{echo round($monthelyflow->results()[$i]->totalizer_diff, 2);} ?></span>
                            </td>
                        </tr>
                        <?php 
                                }
                        ?>
                           
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


</div>






<!-- /.container-fluid -->
<?php include 'includes/footer.php'; ?>
<script>
    $(document).ready(function() {
       var uid=<?php echo $u; ?>;
        setInterval(() => {
            $.ajax({
                type: "POST",
                url: 'get-alarm.php',
                data: { user_id: uid }, 
                success: function(result) {
                    $("#alarm").empty().append(result);
                }
            })
        }, 1000);
    });
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // create chart
    var chart = am4core.create("chartdiv", am4charts.GaugeChart);
    chart.innerRadius = -15;

    var axis = chart.xAxes.push(new am4charts.ValueAxis());
    axis.min = 0;
    axis.max = <?php echo ( $unit->user_limit * $unit->user_today_limit)/100 ; ?> ;
    axis.strictMinMax = true;

    var colorSet = new am4core.ColorSet();

    var gradient = new am4core.LinearGradient();
    gradient.stops.push({
        color: am4core.color("green")
    })
    gradient.stops.push({
        color: am4core.color("yellow")
    })
    gradient.stops.push({
        color: am4core.color("red")
    })

    axis.renderer.line.stroke = gradient;
    axis.renderer.line.strokeWidth = 15;
    axis.renderer.line.strokeOpacity = 1;

    axis.renderer.grid.template.disabled = true;

    var hand = chart.hands.push(new am4charts.ClockHand());
    hand.radius = am4core.percent(97);

    setInterval(function() {
        hand.showValue( <?php if(!empty($unit->c_flow)){ echo $unit->c_flow;}else{ echo 0;} ?> , 1000, am4core.ease.cubicOut);
    }, 2000);
    $("g path").attr().hide();
</script>
<script> 
   
    setTimeout(function(){ $('#short').click(); }, 1000);
</script>
