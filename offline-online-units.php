<?php 

include 'core/init.php';

$db = new User();

$ids = $_POST['ids'];

 $up_ld= $db->selectJoinAll('UPDATE sd_live_panel_status SET date_time = now() WHERE user_id IN ('.$ids.')');
 $up_cs= $db->selectJoinAll('UPDATE comm_status c SET c.status = 1, c.date_time = now() WHERE c.user_id IN ('.$ids.') ');

if($up_cs){
    echo 'cs';
}
if($up_ld){
    echo 'ld';
}


?>