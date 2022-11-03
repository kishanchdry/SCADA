<?php 


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
       
        $con=new PDO('mysql:host=localhost;dbname=scadalast7daysdata', 'root', '');
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        $date= json_decode(stripslashes($_POST['dates']));
        $user_id =  $date[0];
        $c= count($date);
        for($i=1;$i<$c;$i++) {
                    $query="DELETE FROM last_7days_data WHERE date_time  = '$date[$i]' AND user_id = ".$user_id;
                    $qr = $con->query($query);
                    $qr->execute();
        }
    
} 

?>