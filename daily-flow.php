<?php 
include_once 'includes/header.php';
$data = new User();


?>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Daily Flow </a></p>
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
                            <option value="">----Select User----</option>
                                
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
                    <div class="col-xl-8">
                        <h6 class="m-0 font-weight-bold text-primary">Daily Flow Data </h6>
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
                <?php
                    if(isset($_POST['show'])) {
                        $userid =Input::get('user');
                        $user = $data->getData('sd_user', array('user_id', '=', $userid));
                        $user = $user->results()[0];
                ?>        
                <div class="row">
                    <div class="h5 col-xl-4">Unit : <?php echo $user->user_name; ?> </div>
                    <div class="h5 col-xl-4">Limit : <?php echo $user->user_limit; ?></div>
                    <div class="h5 col-xl-4">Date : <?php echo Input::get('date'); ?> </div>
                </div>   
                <hr>
                <?php }?>                 
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>#</th>
                                <th>Hours</th>
                                <th>Flow</th>                                
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                if(isset($_POST['show'])) {

                                        $userid =Input::get('user');
                                        $date=Input::get('date');
                                        $dd=date_create_from_format('Y-m-d', $date);    
                                        $day = date_format($dd, 'Y_m_d');
                                        $table= 'day_'.$day;

                                        try{
                                            
                                                $con=new PDO('mysql:host=localhost;dbname=pali_history', 'root', 'V*ision*123');
                                                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            
                                                
                                                $query="SELECT DISTINCT 
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(0) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(0))) as diff0,  
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(1) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(1))) as diff1,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(2) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(2))) as diff2,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(3) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(3))) as diff3,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(4) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(4))) as diff4,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(5) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(5))) as diff5,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(6) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(6))) as diff6,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(7) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(7))) as diff7,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(8) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(8))) as diff8,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(9) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(9))) as diff9,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(10) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(10))) as diff10,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(11) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(11))) as diff11,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(12) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(12))) as diff12,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(13) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(13))) as diff13,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(14) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(14))) as diff14,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(15) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(15))) as diff15,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(16) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(16))) as diff16,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(17) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(17))) as diff17,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(18) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(18))) as diff18,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(19) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(19))) as diff19, 
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(20) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(20))) as diff20,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(21) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(21))) as diff21,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(22) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(22))) as diff22,
                                                ((SELECT MAX(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(23) )-(SELECT MIN(totalizer) FROM $table WHERE user_id = ".$userid." AND HOUR(date_time) =(23))) as diff23 
                                                FROM $table GROUP BY user_id ORDER BY date_time";

                                                $qr = $con->query($query);
                                                $qr->setFetchMode(PDO::FETCH_ASSOC);
                                                $data = $qr->fetchAll();
                                               
                                                $c=24;
                                                $r=1;
                                                for($i=0;$i<$c;$i++){
                                                ?>
                                                 <tr>
                                                    <th><?php echo $r; ?></th>
                                                    <td><?php echo $i ; echo '-'; echo $i+1; ?></td>
                                                    <td><?php echo $data[0]['diff'.$i]/100; ?></td>
                                                </tr>
                                                <?php
                                                        $r++;
                                                        //$i++;
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

