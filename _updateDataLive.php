<?php


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
       include 'core/init.php';
        $db =  new User();
        $units= explode(',', $_POST['units']);
        $limits= explode(',', $_POST['limits']);
        $totalizers= explode(',', $_POST['totalizers']);
        $r = count($units);

       for($i=0;$i<$r;$i++){
            $db->updateData('sd_live_panel_status', $units[$i], 'user_id', array(
                    'totalizer' => $totalizers[$i] + $limits[$i],
                    'today_flow' => $limits[$i],
                    'date_time' => date('Y-m-d H:i:s')
                ));          
            
       }


} 

?>