<?php 
include_once 'includes/header.php';
$user_id = $_GET['u'];
$data = new User();

$unit = $data->getData('sd_user', array('user_id', '=', $user_id));
$unit=$unit->results()[0];

if(Input::exists()){
    if (Token::check(Input::get('token'))) {
        
         
            $salt = Hash::salt(32);

            try{

               $unit= $data->updateData('sd_user',$user_id, 'user_id', array(
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
                        'user_password' => Hash::make(Input::get('password'), $salt),
                        'randSalt'=>$salt
                ));

                $data->adding('updates', array(
                    'user_id' => $user_id,
                    'flow' => '-',
                    'kldlimit' => Input::get('flow_limit'),
                    'times' => '-',
                    'user' => $user
                ));

              
                $data->updateData('comm_status', $user_id, 'user_id', array(
                        'zone_id' => Input::get('zone'),
                        'phase_id' => Input::get('phase')                  
                ));

            }catch(Exception $e){
                die($e->getMessage());
            }

         
                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Success :-
                                <div class="text-white-80 small">New Unit Updated !</div>
                            </div>
                        </div>';
           
                       Redirect::to('update.php');
            
       
    }

}

if($role == 1){
?>

<style>
  #plc_reset{
    opacity:0;
  }
</style>

<?php } ?>

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
                                <option <?php if($unit->user_location ==$phase->results()[$i]->phase_id ){echo 'selected';} ?> value="<?php echo $phase->results()[$i]->phase_id; ?>"><?php echo $phase->results()[$i]->phase_name; ?></option>
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
                                <option <?php if($unit->user_zone ==$phase->results()[$i]->zone_id ){echo 'selected';} ?> value="<?php echo $zone->results()[$i]->zone_id; ?>"><?php echo $zone->results()[$i]->zone_name; ?></option>
                                   <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Name :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" name='name' class='form-control form-control-sm' value="<?php echo escape($unit->user_name); ?>" placeholder="Enter Name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Panel No. :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape($unit->user_panel_no); ?>" name='panel_no' class='form-control form-control-sm' placeholder="Enter Panel Number" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Address  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <textarea name='address' class='form-control form-control-sm' placeholder="Enter Address"> <?php echo escape($unit->user_address); ?> </textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Opt. Name  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape($unit->user_opt_name); ?>" name='opt_name' class='form-control form-control-sm' placeholder="Enter Optional Name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Opt. Mobile  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape($unit->user_opt_mobile); ?>" name='opt_mobile' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Flow Limit (KLD)  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape($unit->user_limit); ?>" name='flow_limit' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Today Limit (%)  :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="text" value="<?php echo escape($unit->user_today_limit); ?>" name='today_limit' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            PLC Reset :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <select name="plc_reset" id="plc_reset" class="form-control form-control-sm ">
                                <option value="">---Select----</option>
                                <option <?php if($unit->user_plc_reset ==1 ){echo 'selected';} ?> value="1">ON </option>
                                <option <?php if($unit->user_plc_reset==0 ){echo 'selected';} ?> value="0">OFF </option>
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
                                <option <?php if($unit->user_panel_lock ==1 ){echo 'selected';} ?> value="1">Lock</option>
                                <option <?php if($unit->user_panel_lock ==0 ){echo 'selected';} ?> value="0">Unlock</option>
                            </select>               
                        </div>
                    </div>
                
                    
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            <input type="hidden"  name="token" value="<?php echo Token::generate(); ?>" />
                            <input type="submit" class="btn btn-primary"  name="register" value="Update-Unit" />
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
                            <option <?php if($unit->user_mode ==1 ){echo 'selected';} ?> value="1">Auto</option>
                            <option <?php if($unit->user_mode ==0 ){echo 'selected';} ?> value="0">Manual</option>
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
                            <option <?php if($unit->user_reset_totalizer ==1 ){echo 'selected';} ?> value="1">ON</option>
                            <option <?php if($unit->user_reset_totalizer ==0 ){echo 'selected';} ?> value="0">OFF</option>
                        </select> 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        PLC Mode Reset  :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <select  name="mode_plc_reset" id="" class="form-control form-control-sm " >
                            <option value="">---Select----</option>
                            <option <?php if($unit->user_reset_memory ==1 ){echo 'selected';} ?> value="1">ON</option>
                            <option <?php if($unit->user_reset_memory ==0 ){echo 'selected';} ?> value="0">OFF</option>
                        </select> 
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
                        <input type="text" value="<?php echo escape($unit->user_username); ?>" name='username' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        Password :
                    </div>
                    <div class="col-sm-7 mb-3 mb-sm-0">
                        <input type="password" value="<?php echo escape($unit->user_password); ?>" name='password' class='form-control form-control-sm ' placeholder="Enter Optional Mobile" />
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    
</div>


<?php include_once 'includes/footer.php'; ?>

