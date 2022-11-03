<?php 
include_once 'includes/header.php';
$data = new User();
if(Input::exists()){
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation =$validate->check($_POST, array(
            'phase' => array(
                'required' => true
            ),
            'zone' => array(
                'required' => true
            ),
            'name' => array(
                'required' => true
            ),
            'panel_no' => array(
                'required' => true
            ),
            'address' => array(
                'required' => true
            ),
            'opt_name' => array(
                'required' => true
            ),
            'opt_mobile' => array(
                'required' => true
            ),
            'flow_limit' => array(
                'required' => true
            ),
            'today_limit' => array(
                'required' => true
            ),
            'username' => array(
                'required' => true
            ),
            'password' => array(
                'required' => true
            )

        ));

        if ($validation->passed()) {
            
         
            $salt = Hash::salt(32);

            try{
               $unit= $data->adding('sd_user', array(
                        'user_location' => Input::get('phase'),
                        'user_zone' => Input::get('zone'),
                        'user_name' => Input::get('name'),
                        'user_panel_no' => Input::get('panel_no'),
                        'user_address' => Input::get('address'),
                        'user_opt_name' => Input::get('opt_name'),
                        'user_opt_mobile' => Input::get('opt_mobile'),
                        'user_limit' => Input::get('flow_limit'),
                        'user_today_limit' => Input::get('today_limit'),
                        'user_plc_reset' => Input::get('plc_reset'),
                        'user_panel_lock' => Input::get('panel_lock'),
                        'user_mode' => Input::get('mode'),
                        'user_reset_totalizer' => Input::get('reset_totalizer'),
                        'user_reset_memory' => Input::get('mode_plc_reset'),
                        'user_username' => Input::get('username'),
                        'panel_unlock_timing' => Input::get('panel_unlock_timing'),
                        'user_password' => Hash::make(Input::get('password'), $salt),
                        'randSalt'=>$salt
                ));

                $user_name =Input::get('username');
                $user_id = $data->getData('sd_user', array('user_username', '=', $user_name));
                $user_id= $user_id->results()[0]->user_id;

                $data->adding('sd_live_panel_status', array(
                    'user_id' => $user_id,
                    'date_time' => date('Y-m-d H:i:s')
              
                ));

                $data->adding('start_totalizer', array(
                        'id' =>  $user_id,
                        'tdate' => date('Y-m-d H:i:s'), 
                        'm_totalizer' => 0
                    ));

                $data->adding('comm_status', array(
                        'user_id' => $user_id,
                        'status' => 3,
                        'zone_id' => Input::get('zone'),
                        'phase_id' => Input::get('phase'),
                        'date_time' => date('Y-m-d H:i:s')
                  
                ));
            }catch(Exception $e){
                die($e->getMessage());
            }

         
                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Success :-
                                <div class="text-white-80 small">New Unit Added !</div>
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
        <p><a href="settings.php">Settings </a> >> <a href="#">Add Unit</a></p>
</div>



<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Unit Details </h6>
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
                            <select class="form-control form-control-sm"  name="phase">
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
                            <?php
                                $zone = $data->selectAll('sd_industrial_zone');
                                $c=$zone->count();
                            ?>
                            <select class="form-control form-control-sm"  name="zone">
                                <option value="">----Select Zone----</option>
                                <?php 
                                   for($i=0;$i<$c;$i++){
                                ?>
                                <option value="<?php echo $zone->results()[$i]->zone_id; ?>"><?php echo $zone->results()[$i]->zone_name; ?></option>
                                   <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Name :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape(Input::get("name")); ?>" name='name' class='form-control form-control-sm' value="<?php echo escape(Input::get("name")); ?>" placeholder="Enter Name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Panel No. :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape(Input::get("panel_no")); ?>" name='panel_no' class='form-control form-control-sm' placeholder="Enter Panel Number" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Address  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <textarea name='address' class='form-control form-control-sm' placeholder="Enter Address"> <?php echo escape(Input::get("address")); ?> </textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Opt. Name  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape(Input::get("opt_name")); ?>" name='opt_name' class='form-control form-control-sm' placeholder="Enter Optional Name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Opt. Mobile  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape(Input::get("opt_mobile")); ?>" name='opt_mobile' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Flow Limit (KLD)  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape(Input::get("flow_limit")); ?>" name='flow_limit' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Today Limit (%)  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape(Input::get("today_limit")); ?>" name='today_limit' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            PLC Reset :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <select name="plc_reset" id="" class="form-control form-control-sm ">
                                <option value="">---Select----</option>
                                <option value="1">ON </option>
                                <option  selected value="0">OFF </option>
                            </select>                   
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Panel Lock :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <select  name="panel_lock" id="" class="form-control form-control-sm " >
                                <option value="">---Select----</option>
                                <option value="1">Lock</option>
                                <option selected value="0">Unlock</option>
                            </select>               
                        </div>
                    </div>
                
                    
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            <input type="hidden"  name="token" value="<?php echo Token::generate(); ?>" />
                            <input type="submit" class="btn btn-primary"  name="register" value="Add-Unit" />
                            <br>
                            <br>
                        </div>
                        <br>
                    </div>
               
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Auto Shedular </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        Mode  :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <select  name="mode" id="" class="form-control form-control-sm " >
                            <option value="">---Select----</option>
                            <option value="1">Auto</option>
                            <option selected value="0">Manual</option>
                        </select> 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        Reset Totalizer  :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <select name="reset_totalizer" id="" class="form-control form-control-sm " >
                            <option value="">---Select----</option>
                            <option value="1">ON</option>
                            <option selected value="0">OFF</option>
                        </select> 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        PLC Reset  :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <select  name="mode_plc_reset" id="" class="form-control form-control-sm " >
                            <option value="">---Select----</option>
                            <option value="1">ON</option>
                            <option selected value="0">OFF</option>
                        </select> 
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                       Panel Unlock Timing  :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                         <input type="text" value="<?php echo escape(Input::get("panel_unlock_timing")); ?>" name='panel_unlock_timing' class='form-control form-control-sm ' placeholder="Enter Panel Unlock Timing" />
                    </div>
                    <div class="col-sm-12 mb-3 mb-sm-0" style='font-size:12px;'>
                         <p class='text-danger'> Note : Pattern to enter the timing to ulock panels. Example - 09-12,15-18 (24 Hours Formet & Each shift need to saperated by comma (,)) </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Login Details </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        User Name :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <input type="text" value="<?php echo escape(Input::get("username")); ?>" name='username' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        Password :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <input type="password" value="<?php echo escape(Input::get("password")); ?>" name='password' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    
</div>


<?php include_once 'includes/footer.php'; ?>
