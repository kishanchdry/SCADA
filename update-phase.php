<?php 

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    include_once 'core/init.php';
    $data = new User();
   
            try {
                $phase_limit=$_POST['pl'];
                $phase_state =$_POST['phs'];
                $phase_id =$_POST['pid'];

               
                $result1 = $data->updateData('sd_industrial_phase', $phase_id, 'phase_id', array(
                                "phase_limit"=>$phase_limit,
                                "phase_state" =>$phase_state
                )); 
                
                $result2 = $data->updateData('sd_user', $phase_id, 'phase_id', array(
                    "user_today_limit"=>$phase_limit,
                    "user_panel_lock" =>$phase_state
                )); 

                $result3 = $data->updateData('sd_industrial_phase_zone', $phase_id, 'phase_id', array(
                    "zone_limit"=>$phase_limit,
                    "zone_state" =>$phase_state
                ));

                
                echo 'ok';
            } catch (Exception $e) {
                die($e->getMessage());
            }
    
}

?>