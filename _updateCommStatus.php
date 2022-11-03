<?php


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
       include 'core/init.php';
        $db =  new User();
        $units= $_POST['units'];     
     
        $db->selectJoinAll('UPDATE comm_status SET status = 1, date_time = now() WHERE user_is IN ('.$units.')');
            
       

} 

?>