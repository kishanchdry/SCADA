<?php 

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    include_once 'core/init.php';
    $data = new User();
   
            try {
                $phase_id=$_POST['phase_id'];
                $zone_id=$_POST['zone_id'];
                $zone_limit=$_POST['zone_limit'];
                $zone_state=$_POST['zone_state'];
                $zone_plc=$_POST['zone_plc'];

                
                $result1 = $data->SetQuery('UPDATE sd_industrial_phase_zone z  SET z.zone_limit= '.$zonelimit.', z.zone_state = '.$zonestate.', z.zone_plc_reset='.$plc.' where z.zone_id = '.$zoneid.' AND z.phase_id = '.$phaseid); 
                
                $result1 = $data->SetQuery('UPDATE sd_user u  SET u.user_today_limit = '.$zonelimit.', u.user_panel_lock = '.$zonestate.', u.user_plc_reset='.$plc.' where u.user_location = '.$phaseid.' AND u.user_zone = '.$zoneid);
                
                echo 'ok';
            } catch (Exception $e) {
                die($e->getMessage());
            }
    
}

?>