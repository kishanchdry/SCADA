<?php 


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
  
    $phaseid=$_POST['phase_id'];
    $z_query ="SELECT z.zone_id ,z.zone_name FROM sd_industrial_zone z WHERE z.zone_id NOT IN (SELECT pz.zone_id FROM sd_industrial_phase_zone pz WHERE  pz.phase_id = ".$phaseid.")";

    $zoneData= $data->selectJoinAll($z_query);
    if(!empty($zoneData)){
        $c=$zoneData->count();
    
        for ($i=0; $i<$c; $i++) {
        
            echo '<option value="'.$zoneData->results()[$i]->zone_id.'">'.$zoneData->results()[$i]->zone_name.'</option>';
        }
    }else{
        echo '<option value="-">No data found !</option>';
    }
}

?>