<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Panel Offline </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Select Date/Time(Hour) </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Location :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <?php
                                $phase = $data->selectAll('sd_industrial_phase');
                                $c=$phase->count();
                            ?>
                            <select class="form-control form-control-sm" onchange="get_zone(this.value)" id="phase"  name="location">
                                <option value="">----Select Location----</option>
                                <?php 
                                   for($i=0;$i<$c;$i++){
                                ?>
                                <option value="<?php echo $phase->results()[$i]->phase_id; ?>"><?php echo $phase->results()[$i]->phase_name; ?></option>
                                   <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Zone :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            
                            <select class="form-control form-control-sm" name="zone"  id="zones">
                                <option value="">----Select Zone----</option>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Date :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="date" name="date" required class="form-control " id="Date"
                                value="<?php echo escape(Input::get("date")); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Hour :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <select class="form-control form-control-sm" name="hour" required  id="hour">
                                <option value="">----Select Hour----</option>
                                <option value="01">1</option>
                                <option value="02">2</option>
                                <option value="03">3</option>
                                <option value="04">4</option>
                                <option value="05">5</option>
                                <option value="06">6</option>
                                <option value="07">7</option>
                                <option value="08">8</option>
                                <option value="09">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="00">24</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            
                            <input type="submit" class="btn btn-primary" name="show" value="Submit" />
                            <br>
                            <br>
                        </div>
                        <br>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <div class="col-lg-8">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-8">
                        <h6 class="m-0 font-weight-bold text-primary">Panel Offline Report  </h6>
                    </div>
                    <div class="col-xl-4 text-right">
                        <button onclick="export_excel('dataTable')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-excel"></i>
                        </button>

                        <button title="Export to PDF" onclick="getPDF('dataTable')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">     
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>#</th>
                                <th>Unit</th>
                                <th>Location</th>
                                <th>Zone</th>    
                                <th>Address</th>   
                                <th>Totalizer</th>  
                                <th>Offline DateTime</th>                       
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                if(isset($_POST['show'])) {

                                    $location =Input::get('location');
                                    $zone=Input::get('zone');
                                    $date=Input::get('date');
                                    $hour=Input::get('hour');

                                    if(empty($location) && empty($zone)){
                                        $q = 'Select u.user_name, u.user_address, p.phase_name, z.zone_name, o.totalizer as offlineTotalizer, o.date_time as oflineDateTime from panel_offline o LEFT JOIN sd_user u ON o.user_id = u.user_id LEFT JOIN sd_industrial_phase p ON p.phase_id = u.user_location LEFT JOIN sd_industrial_zone z ON z.zone_id = u.user_zone WHERE o.panel_status = 1 AND date(o.date_time) = "'.$date.'" AND HOUR(o.date_time) = "'.$hour.'"  ORDER BY p.phase_name ASC, z.zone_name ASC, u.user_address ASC, u.user_name ASC';
                                    }else if(!empty($location) && empty($zone)){
                                        $q = 'Select u.user_name, u.user_address, p.phase_name, z.zone_name, o.totalizer as offlineTotalizer, o.date_time as oflineDateTime from panel_offline o LEFT JOIN sd_user u ON o.user_id = u.user_id LEFT JOIN sd_industrial_phase p ON p.phase_id = u.user_location LEFT JOIN sd_industrial_zone z ON z.zone_id = u.user_zone WHERE o.panel_status = 1 AND date(o.date_time) = "'.$date.'" AND HOUR(o.date_time) = "'.$hour.'" AND u.user_location ='.$location.' ORDER BY p.phase_name ASC, z.zone_name ASC, u.user_address ASC, u.user_name ASC';
                                    }else if(!empty($location) && !empty($zone)){
                                        $q = 'Select u.user_name, u.user_address, p.phase_name, z.zone_name, o.totalizer as offlineTotalizer, o.date_time as oflineDateTime from panel_offline o LEFT JOIN sd_user u ON o.user_id = u.user_id LEFT JOIN sd_industrial_phase p ON p.phase_id = u.user_location LEFT JOIN sd_industrial_zone z ON z.zone_id = u.user_zone WHERE o.panel_status = 1 AND date(o.date_time) = "'.$date.'" AND HOUR(o.date_time) = "'.$hour.'" AND u.user_location ='.$location.' AND u.user_zone = '.$zone.' ORDER BY p.phase_name ASC, z.zone_name ASC, u.user_address ASC, u.user_name ASC';
                                    }
                                   
                                    $panel_offline = $data->selectJoinAll($q);
                                    $r = $panel_offline->count();
                                    for($i=0;$i<$r;$i++){
                                    ?>
                                        <tr>
                                            <th><?php echo $i+1; ?></th>
                                            <td><?php echo $panel_offline->results()[$i]->user_name; ?></td>
                                            <td><?php echo $panel_offline->results()[$i]->phase_name; ?></td>
                                            <td><?php echo $panel_offline->results()[$i]->zone_name; ?></td>
                                            <td><?php echo $panel_offline->results()[$i]->user_address; ?></td>
                                            <td><?php echo $panel_offline->results()[$i]->offlineTotalizer; ?></td>
                                            <td><?php echo date('M d Y h:i A', strtotime($panel_offline->results()[$i]->oflineDateTime)); ?></td>
                                        </tr>
                                    <?php
                                       
                                    }                                          
                                }       
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<?php include_once 'includes/footer.php'; ?>

