<?php 
include_once 'includes/header.php';
$db = new User();



?>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Single Unit monthly flow Report </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Search </h6>
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
                                $phase = $db->selectAll('sd_industrial_phase');
                                $c=$phase->count();
                            ?>
                            <select class="form-control form-control-sm" onchange="get_zone(this.value)" id="phase"  name="phase">
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
                           
                            <select class="form-control form-control-sm" name="zone"  onchange="get_users(this.value, $('#phase').val())"  id="zones">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Unit :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                          
                            <select class="form-control form-control-sm" id="users"   name="user">
                            <option value="">----Select user----</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Date :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="date" name="date" class="form-control " id="Date"
                                value="<?php echo escape(Input::get("date")); ?>">
                        </div>
                    </div>
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
                    <div class="col-xl-6">
                        <h6 class="m-0 font-weight-bold text-primary">Monthly Flow Report </h6>
                    </div>
                    <div class="col-xl-6 text-right">
                    <a  id="ecportExcel" title="myTable" class="btn btn-success btn-circle btn-sm text-light">
                            <i class="fas fa-file-excel  text-light"></i>
                        </a>

                        <button title="Export to PDF" onclick="getPDF('dataTable')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <form method="post" >                                          
                <div class="table-responsive">
                <table class="table table-bordered table-sm" id="myTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Area</th>
                                <th>Zone</th>
                                <th>Address</th>
                                <th>Limit (KLD)</th>
                                <th>Date-Time </th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                if(isset($_POST['show'])) {

                                        $phase =Input::get('phase');
                                        $zone =Input::get('zone');
                                        $userid =Input::get('user');
                                        $date=Input::get('date');

                                        try{
                                            $query = 'SELECT t.id as tid, t.totalizer_diff, t.tdate, u.*, p.phase_name FROM `tester` t INNER JOIN sd_user u ON u.user_id = t.id LEFT JOIN sd_industrial_phase p ON p.phase_id = u.user_location WHERE u.user_location = '.$phase.' AND u.user_zone ='.$zone.' AND MONTH(t.tdate) = MONTH("'.$date.'") AND u.user_id ='. $userid;
                                        
                                            $data = $db->selectJoinAll($query);
                                            if (!empty($data)) {
                                                $c = $data->count();
                                            
                                                for ($i=0; $i<$c;$i++) {
                                                    ?>
                                                <tr>
                                                    <td><?php print $i+1; ?></td>
                                                    <td><?php print $data->results()[$i]->user_name; ?></td>
                                                    <td><?php print $data->results()[$i]->phase_name; ?></td>
                                                    <td>Zone <?php print $data->results()[$i]->user_zone; ?></td>
                                                    <td><?php print $data->results()[$i]->user_address; ?></td>
                                                    <td><?php print $data->results()[$i]->user_limit; ?></td>
                                                    <td><?php print $data->results()[$i]->tdate; ?></td>
                                                    <td>                                                       
                                                          <?php if( $data->results()[$i]->totalizer_diff != '1.00'){echo $data->results()[$i]->totalizer_diff;}else{ echo '-';} ?>
                                                    </td>
                                                   
                                                    
                                                </tr>
                                                <?php
                                                }
                                                $query1 = 'SELECT sum(t.totalizer_diff) as totalDiff FROM `tester` t INNER JOIN sd_user u ON u.user_id = t.id LEFT JOIN sd_industrial_phase p ON p.phase_id = u.user_location WHERE u.user_location = '.$phase.' AND u.user_zone ='.$zone.' AND MONTH(t.tdate) = MONTH("'.$date.'") AND u.user_id ='. $userid;
                                        
                                                $data1 = $db->selectJoinAll($query1);
                                               
                                                ?>
                                                <tr>
                                                    <th colspan='7' > Total</th>
                                                    <th><?=$data1->results()[0]->totalDiff;?></th>
                                                </tr>
                                                <?php
                                            }
                                            }catch(Exception $e){
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

<script>

$(document).ready(function() {
 
    $('#myTable').DataTable();

    
});

</script>