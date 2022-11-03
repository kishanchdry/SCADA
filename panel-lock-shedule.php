<?php 
include_once 'includes/header.php';
$data = new User();
if(Input::exists()){
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation =$validate->check($_POST, array(
            'location' => array(
                'required' => true
            ),
            'address' => array(
                'required' => true
            ),
            'operator_name' => array(
                'required' => true,
                'min' => 4,
                'max' => 70,
            ),
            'operator_mobile' => array(
                'required' => true
            )
        ));

        if ($validation->passed()) {
            
            try{
                $data->adding('sd_industrial_phase', array(
                    'phase_name' => Input::get('location'),
                    'phase_address' => Input::get('address'),
                    'op_name' => Input::get('operator_name'),
                    'op_mobile' => Input::get('operator_mobile'),
                ));
            }catch(Exception $e){
                die($e->getMessage());
            }

            // to show flash notification to another page
           // Redirect::to('deshboard.php');
          // Session::flash('Registered', 'New user registered !');
           
          
                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Registered :-
                                <div class="text-white-80 small">New Location Added !</div>
                            </div>
                        </div>';
           
                       // Redirect::to('add-organization.php');
            
        } else {
            
            echo    '<div id="notification" class="card bg-danger text-white shadow">
                        <div class="card-body">
                            Errors :-';
                  
            foreach($validation->errors() as $errors){
                        echo ' <div class="text-white-80 small">'.$errors.'</div>';
            }
           
            echo        '</div>
                    </div>';
           
        }
    }

}


?>


<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Panel Lock Shedule</a></p>
</div>


<div class="row  mb-4" id='top'>

    <div class="col-lg-6">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Lock / Un-Locak Panel by Limit  </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            limit :
                        </div>
                        <div class="col-sm-9 mb-3 mb-sm-0">
                            <select name="limit" class="form-control form-control-sm"  required>
                                <option value="">---Select Section----</option>
                                <option value="<">< 50 </option>
                                <option value=">">> 50</option>
                            </select>
                        </div>
                    </div>
                   
                    <hr>
                    <div class="row">
                        <div class="col-sm-8 text-right">
                            <input type="submit" class="btn btn-danger" name="limit-lock" value="Lock" />
                            <br>
                            <br>
                        </div>
                        <div class="col-sm-3 text-right">
                          
                            <input type="submit" class="btn btn-success" name="limit-un-lock" value="Un-Lock" />
                            <br>
                            <br>
                        </div>
                        <br>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <?php
    
    if(isset($_POST['limit-lock'])){
        try {
                
                $limit= $_POST['limit'];
                $query2= "UPDATE sd_user SET user_panel_lock = 1 WHERE user_limit $limit 50 ";
                $data->selectJoinAll($query2);
                
                echo "<script>alert('Panel Locked !');</script>";
            
            } catch(PDOException $e) {
            echo $e->getMessage();
            }
        }
        if(isset($_POST['limit-un-lock'])){
            try {
                    
                $limit= $_POST['limit'];
                $query2= "UPDATE sd_user SET user_panel_lock = 0 WHERE user_limit $limit 50 ";
                $data->selectJoinAll($query2);

                echo "<script>alert('Panel Un-Locked !');</script>";
               
                } catch(PDOException $e) {
                echo $e->getMessage();
                }
        }   
    
    ?>

    <div class="col-lg-6">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Lock / Un-Locak Panel All  </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-7">
                                Lock / Un-Lock All Panels :
                        </div>

                        <div class="col-sm-2 text-right">
                            <input type="submit" class="btn btn-danger" name="all-lock" value="All Lock" />
                          
                            <br>
                        </div>
                        <div class="col-sm-3 text-right">
                          
                            <input type="submit" class="btn btn-success" name="all-un-lock" value="All Un-Lock" />
                            
                        </div>
                       
                    </div>
                </form>

            </div>
        </div>

    </div>
    
</div>
<?php
    
    if(isset($_POST['all-lock'])){
        try {
                
                $query2= "UPDATE sd_user SET user_panel_lock = 1 WHERE panel_lock_shedule_mode = 0";
                $data->selectJoinAll($query2);
                
                echo "<script>alert('Panel Locked !');</script>";
            
            } catch(PDOException $e) {
            echo $e->getMessage();
            }
        }
        if(isset($_POST['all-un-lock'])){
            try {
              
                $query2= "UPDATE sd_user SET user_panel_lock = 0 WHERE panel_lock_shedule_mode = 0";
                $data->selectJoinAll($query2);

                echo "<script>alert('Panel Un-Locked !');</script>";
               
                } catch(PDOException $e) {
                echo $e->getMessage();
                }
        }   
    
    ?>


<div class="row mb-4" >

    <div class="col-lg-6">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Shedule Lock / Un-Locak Panel   </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php 
                    $sheduled = $data->selectJoinAll('SELECT * FROM panel_lock_shedule ORDER BY id DESC LIMIT 0, 1');
                    if (empty($sheduled)) {
                        ?>
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            Start Time :
                        </div>
                        <div class="col-sm-9 mb-3 mb-sm-0">
                            <input type="time" name="start" class="form-control form-control-sm"  required />
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            End Time :
                        </div>
                        <div class="col-sm-9 mb-3 mb-sm-0">
                            <input type="time" name="end" class="form-control form-control-sm"  required />
                            
                        </div>
                    </div>
                
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <input type="submit" class="btn btn-success" name="s-lock" value="Shedule Lock" />
                            <br>
                            <br>
                        </div>
                        
                        <br>
                    </div>
                </form>
                <?php
                    }else{            
                ?>
                <form class="user" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <input type="submit" class="btn btn-danger" name="s-un-lock" value="Un-Lock Sheduled Panel" />
                            <br>
                            <br>
                        </div>
                        
                        <br>
                    </div>
                </form>
                <?php } ?>
            </div>
        </div>

    </div>
<?php

if(isset($_POST['s-lock'])){
    try {
            
            $start_time = $_POST['start'];
            $end_time = $_POST['end'];
          
           
            $data->adding('panel_lock_shedule', array(
                'start' => $start_time,
                'end' => $end_time,
                'sheduled_date_time' => date('Y-m-d H:i:s')
            ));
            
            echo "<script>alert('Panel Lock Sheduled !');</script>";
            Redirect::to('panel-lock-shedule.php');
        
        } catch(PDOException $e) {
        echo $e->getMessage();
        }
    }

    if(isset($_POST['s-un-lock'])){
        try {
               
               
                $data->selectJoinAll('TRUNCATE TABLE `panel_lock_shedule`');
                
                echo "<script>alert('Panel Un-Locked !');</script>";

                Redirect::to('panel-lock-shedule.php');
            
            } catch(PDOException $e) {
            echo $e->getMessage();
            }
        }    
     

?>

    <div class="col-lg-6">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Sheduled Panel Lock / Un-Locak Details</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>#</th>
                                <th>Start Time</th>
                                <th>End Time </th>
                                <th>Sheduled On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!empty($sheduled)){
                                    $c = $sheduled->count();
                                    for($i=0;$i<$c;$i++){
                                        ?>
                                        <tr class="bg-info text-light">
                                            <td><?php echo $i+1; ?></td>
                                            <td><?php echo $sheduled->results()[$i]->start; ?></tdth>
                                            <td><?php echo $sheduled->results()[$i]->end; ?> </td>
                                            <td><?php echo $sheduled->results()[$i]->sheduled_date_time; ?></td>
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



<?php include_once 'includes/footer.php'; ?>

