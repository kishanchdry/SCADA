<?php 
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
  
    $phaseid=$_POST['phase_id'];
    $zoneid = $_POST['zone_id'];

    $query ="SELECT user_id, user_name FROM sd_user WHERE  user_location = ".$phaseid." AND user_zone = ".$zoneid;

    $users= $data->selectJoinAll($query);
    $c=$users->count();
   
    for ($i=0; $i<$c; $i++) {
       
        echo '<div class="form-check"> <label class="form-check-label" for="check2"> <input type="checkbox" class="form-check-input" id="unit" name="unit" value="'.$users->results()[$i]->user_id.'" onclick="userFunction(this)">'.$users->results()[$i]->user_name.'</label> </div>';
    }
}

?>