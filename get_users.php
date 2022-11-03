<?php 
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
  
    $phaseid=$_POST['phase_id'];
    $zoneid = $_POST['zone_id'];

     $query ="SELECT user_id, user_name FROM sd_user WHERE user_zone = ".$zoneid." AND  user_location = ".$phaseid;

    $users= $data->selectJoinAll($query);
    $c=$users->count();
   
    for ($i=0; $i<$c; $i++) {
       
        echo '<option value="'.$users->results()[$i]->user_id.'">'.$users->results()[$i]->user_name.'</option>';
    }
}

?>