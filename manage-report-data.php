<?php 
include_once 'includes/header.php';
$data = new User();



?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Manage Report Data </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Search  </h6>
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
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Report Data</h6>                       
                    </div>
                </div>
            </div>
            <div class="card-body">
            <form method="post" >                                          
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Date-Time </th>
                                <th>Value</th>
                                <th>Delete</th>
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
                                           $query = 'SELECT t.t_id, t.id, t.totalizer_diff,t.tdate FROM `tester` t INNER JOIN sd_user u ON u.user_id = t.id WHERE u.user_location = '.$phase.' AND u.user_zone ='.$zone.' AND  YEAR(t.tdate) = YEAR("'. $date.'") AND MONTH(t.tdate) = MONTH("'. $date.'") AND DAY(tdate) >= DAY("'. $date.'") AND u.user_id ='. $userid;
                                        
                                            $data = $data->selectJoinAll($query);
                                            if (!empty($data)) {
                                                $c = $data->count();
                                            
                                                for ($i=0; $i<$c;$i++) {
                                                    ?>
                                                <tr>
                                                    <td><?php print $i+1; ?></td>
                                                    <td><?php print$data->results()[$i]->tdate; ?></td>
                                                    <td>
                                                       
                                                            <input type="text" name='totalizer[]' value="<?php echo $data->results()[$i]->totalizer_diff; ?>"  />
                                                            <input type="hidden" name="id[]" value="<?php echo $data->results()[$i]->t_id; ?>" />
                                                    </td>
                                                   
                                                    <td><i class="text-danger fa fa-times-circle cursor-pointer" ></i></td>
                                                </tr>
                                                <?php
                                                }
                                            }
                                            }catch(Exception $e){
                                                die($e->getMessage());
                                            }
                                        }
                            ?>

                        </tbody>
                    </table>                   
                </div>
                <input type="submit" name='submit' class="btn btn-info btn-sm float-right" value="Update" />
                    </form>
                    <?php
                    
                    if(isset($_POST['submit'])){
   
                        $id = $_POST['id'];
                        $totalizer = $_POST['totalizer'];
                       $c= count($id);
                        try {
                            for($i=0;$i<$c;$i++){
                                $data->updateData('tester', $id[$i], 't_id', array(
                                    'totalizer_diff' => $totalizer[$i]
                                ));
                            }
                            echo    '<div id="notification" class="card bg-success text-white shadow">
                                        <div class="card-body">
                                        Success :-
                                            <div class="text-white-80 small">Data Updated !</div>
                                        </div>
                                    </div>';
            
                            // Redirect::to('add-organization.php');
                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                
            }

                    ?>
            </div>
        </div>

    </div>
</div>


<?php include_once 'includes/footer.php'; ?>

