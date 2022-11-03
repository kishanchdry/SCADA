<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Alarm Report </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Select Details </h6>
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
                            From :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="date" name="from" class="form-control " id="Date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            To :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="date" name="to" class="form-control " id="Date">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                         
                            <input type="submit" class="btn btn-primary" name="submit" value="Show" />
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
                        <h6 class="m-0 font-weight-bold text-primary">Alarm Data </h6>
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
                                <th>Panel No.</th>
                                <th>Address</th>
                                <th>Alarm</th>
                                <th>Alarm Start Datetime</th>
                                <!---<th>Alarm End Datetime</th>--->                        
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if (isset($_POST['submit'])) {

                                  $location =Input::get('location');
                                    $zone = Input::get('zone');
                                    $from=Input::get('from');
                                    $to=Input::get('to');

                                    try {
                                        if ($location==0) {
                                            $Lcomp='>';
                                        } else {
                                            $Lcomp = '=';
                                        }
                                        if ($zone==0) {
                                            $Zcomp='>';
                                        } else {
                                            $Zcomp = '=';
                                        }

                                        $query="SELECT 
                                                    u.user_name, 
                                                    u.user_panel_no, 
                                                    u.user_zone,
                                                    u.user_location, 
                                                    u.user_address, 
                                                    a.a_name, 
                                                    a.a_date_time_on,
                                                    a.a_date_time_off,
                                                    a.a_acknoledge
                                                FROM 
                                                    sd_user u  
                                                INNER JOIN 
                                                    alarm a 
                                                ON 
                                                    u.user_id=a.user_id 
                                                WHERE 
                                                    Date(a.a_date_time_on) BETWEEN '$from' AND '$to'    
                                                AND 
                                                    u.user_location ".$Lcomp." ".$location."
                                                AND
                                                    u.user_zone ".$Zcomp." ".$zone."
                                                ORDER BY 
                                                    a.a_date_time_on
                                                DESC";

                                        $alarm = $data->selectJoinAll($query);
                                        if (!empty($alarm)) {
                                            $c=$alarm->count();
                                            
                                            for ($i=0;$i<$c;$i++) {
                                                ?>
                                                <tr>
                                                    <th><?php echo $i+1; ?></th>
                                                    <td><?php echo $alarm->results()[$i]->user_name; ?></td>
                                                    <td><?php echo $alarm->results()[$i]->user_panel_no; ?></td>
                                                    <td><?php echo $alarm->results()[$i]->user_address; ?></td>
                                                    <td><?php echo $alarm->results()[$i]->a_name; ?></td>
                                                    <td><?php echo $alarm->results()[$i]->a_date_time_on; ?></td>
                                                <!--- <td><?php //echo $alarm->results()[$i]->a_date_time_off;?></td>--->
                                                </tr>
                                                <?php
                                            }
                                        }
                                    } catch (Exception $e) {
                                        die($e->getMessage());
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

