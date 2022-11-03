<?php 
include_once 'includes/header.php';
$data = new User();
if(Input::exists()){   
        
        $location = Input::get('location');
        $zone = Input::get('zone');
        $panel_status = Input::get('status');
       
        if(!empty($zone)){
            $q = 'UPDATE sd_user SET user_panel_lock = '.$panel_status.' WHERE user_location='.$location.' AND user_zone ='.$zone;
            $data->selectJoinAll($q);
        }else{
            $q = 'UPDATE sd_user SET user_panel_lock = '.$panel_status.' WHERE user_location='.$location;
            $data->selectJoinAll($q);
        }
       
        if($panel_status == 1){
            $status = "Locked";
        }else{
            $status = "UnLocked";
        }
    
    
        echo    '<div id="notification" class="card bg-success text-white shadow">
                    <div class="card-body">
                    Success :-
                        <div class="text-white-80 small">Panel '.$status.' !</div>
                    </div>
                </div>';

}


?>


<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Panel Lock </a></p>
</div>


<div class="row  mb-4" id='top'>

    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Lock / UnLock  </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Location :
                       
                            <select name="location" onchange="get_zone(this.value)" id="phase" class="form-control form-control-sm"  required>
                                <option value="">---Select Location----</option>
                                <?php
                                    $phase = $data->selectAll('sd_industrial_phase');
                                    $c=$phase->count();
                                   
                                    for($i=0;$i<$c;$i++){
                                ?>
                                    <option value="<?php echo $phase->results()[$i]->phase_id; ?>"><?php echo $phase->results()[$i]->phase_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Zone :
                        
                            <select name="zone" class="form-control form-control-sm" id="zones" >
                                <option value="">---Select Zone----</option>
                               
                            </select>
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Lock / UnLock :
                       
                            <select name="status" class="form-control form-control-sm" >
                                <option value="1">Lock</option>
                                <option value="0">UnLock</option>
                            </select>
                        </div>
                    </div>                   
            </div>
            <div class="card-footer py-3">
                <div class="row">
                    <div class="col-xl-12 text-right">
                        <input type="submit" class="btn btn-success" name="submit" value="Submit" />
                       
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
   



<?php include_once 'includes/footer.php'; ?>

