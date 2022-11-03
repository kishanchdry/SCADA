<?php 


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
    
    $phase=$_POST['phase'];
    $zone = $_POST['zone'];
    $query ="SELECT c.user_id,
                    c.status, 
                    c.date_time as last_comm, 
                    lp.overload, 
                    lp.auto_manual, 
                    lp.c_flow, 
                    lp.today_flow, 
                    lp.panel_lock, 
                    (CASE WHEN (SELECT count(a_id) FROM alarm WHERE user_id = lp.user_id AND date(a_date_time_on) = date(now()) AND a_state = 0 AND a_acknoledge = 0) = 1 THEN 1 ELSE 0 END) as alarm 
                FROM sd_live_panel_status lp 
                LEFT JOIN comm_status c ON lp.user_id = c.user_id
                LEFT JOIN sd_user u ON u.user_id = lp.user_id 
                WHERE u.user_location = $phase AND u.user_zone = $zone
                ORDER BY c.status, u.user_name, u.user_address ASC"; 

    $phaseData= $data->selectJoinAll($query);
    $c=$phaseData->count();
    if(!empty($phaseData) && $c > 0){        
        print_r(json_encode($phaseData->results()));        
    }else{                
        echo 0;
    }
        

}

?>