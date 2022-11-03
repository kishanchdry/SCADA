<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Non-Communicating data </a></p>
</div>



<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Find </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Location :
                        </div>
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <?php
                                $phase = $data->selectAll('sd_industrial_phase');
                            ?>

                            <select class="form-control" name="phase" required onchange="get_zone(this.value)" id="phase">
                            <option value="0">---Select Location---</option>
                                <?php
                                    $c = $phase->count();
                                    for($i=0;$i<$c;$i++){ 
                                ?>
                                    <option value="<?php echo $phase->results()[$i]->phase_id; ?>" ><?php echo $phase->results()[$i]->phase_name; ?></option>
                                <?php } ?>    
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Zone :
                        </div>
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <select class="form-control form-control-sm" name="zone"  onchange="get_users(this.value, $('#phase').val())"  id="zones">
                                <option value="">----Select Zone----</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Date :
                        </div>
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <input type="date"  name="date" class="form-control " id="Date"
                                value="">
                        </div>
                    </div> -->
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                          
                            <input type="submit" class="btn btn-primary" name="show" value="Show" />
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
                    <div class="col-xl-18">
                        <h6 class="m-0 font-weight-bold text-primary">Non-Communicating Units </h6>
                    </div>
                    <div class="col-xl-4  text-right">                       
                        <a  id="ecportExcel" title="dataTable" alt="non_communicating_units" class="btn btn-success btn-circle btn-sm text-light">
                            <i class="fas fa-file-excel  text-light"></i>
                        </a>
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
                                <th>Zone</th>
                                <th>Panel No.</th>
                                <th>Address</th>
                                <th>Last Comm. Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($_POST['show'])) {
                                  
                                    $location = Input::get('phase');
                                    //$date = Input::get('date');
                                    $zone = Input::get('zone');

                                    if(!empty($zone) && !empty($location)){
                                         $query= "SELECT 
                                                   u.user_name, 
                                                   u.user_panel_no, 
                                                   u.user_zone,
                                                   u.user_location, 
                                                   u.user_address, 
                                                   c.date_time as dt
                                               FROM 
                                                   comm_status c
                                               LEFT JOIN 
                                                   sd_user u 
                                               ON 
                                                   u.user_id=c.user_id 
                                               WHERE
                                                    c.status IN (0,2)  
                                                AND
                                                    u.user_location = $zone 
                                                AND
                                                    u.user_zone = $zone
                                               GROUP BY 
                                                   u.user_id 
                                               ORDER BY 
                                                   c.date_time
                                               DESC";
                                   }else if(!empty($location) && empty($zone)){
                                     $query= "SELECT 
                                               u.user_name, 
                                               u.user_panel_no, 
                                               u.user_zone,
                                               u.user_location, 
                                               u.user_address, 
                                               c.date_time as dt
                                           FROM 
                                               comm_status c
                                           LEFT JOIN 
                                               sd_user u 
                                           ON 
                                               u.user_id=c.user_id 
                                           WHERE
                                                c.status IN (0,2)  
                                            AND
                                                u.user_location = $location                                            
                                           GROUP BY 
                                               u.user_id 
                                           ORDER BY 
                                               c.date_time
                                           DESC";
                                    }else{
                                          $query= "SELECT 
                                                    u.user_name, 
                                                    u.user_panel_no, 
                                                    u.user_zone,
                                                    u.user_location, 
                                                    u.user_address, 
                                                    c.date_time as dt
                                                FROM 
                                                    comm_status c
                                                LEFT JOIN 
                                                    sd_user u 
                                                ON 
                                                    u.user_id=c.user_id 
                                                WHERE
                                                    c.status IN (0,2)                                                                
                                                GROUP BY 
                                                    u.user_id 
                                                ORDER BY 
                                                    c.date_time
                                                DESC";
                                    }    

                                        

                                        $units = $data->selectJoinAll($query);

                                        if (!empty($units)) {
                                            $c= $units->count();

                                            for ($i=0;$i<$c;$i++) {
                                                ?>
                                            <tr>
                                                <th><?php echo $i+1; ?></th>
                                                <td><?php echo $units->results()[$i]->user_name; ?></td>
                                                <td>Zone <?php echo $units->results()[$i]->user_zone; ?></td>
                                                <td><?php echo $units->results()[$i]->user_panel_no; ?></td>
                                                <td><?php echo trim($units->results()[$i]->user_address); ?></td>
                                                <td><?php echo $units->results()[$i]->dt; ?></td>
                                            </tr>

                                            <?php
                                            }
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
<script src="js/custom.js"></script>
