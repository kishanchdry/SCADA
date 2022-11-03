<?php 
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
    
    $phase=$_POST['phase'];   
    $query ="SELECT 
                u.user_id, 
                u.user_zone AS zone, 
                TRIM(u.user_name) AS name, 
                TRIM(u.user_address) AS address, 
                u.user_limit, 
                u.user_today_limit, 
                ROUND(SUM(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 1 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t1, 
                ROUND(SUM(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 2 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t2, 
                ROUND(SUM(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 3 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t3, 
                ROUND(SUM(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 4 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t4, 
                ROUND(SUM(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 5 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t5, 
                ROUND(SUM(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 6 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t6 
            FROM sd_user u 
            LEFT JOIN comm_status c ON c.user_id = u.user_id 
            LEFT JOIN tester t ON u.user_id = t.id 
            WHERE u.user_location = $phase 
            GROUP BY u.user_id, u.user_zone, u.user_name, u.user_address, u.user_limit, u.user_today_limit
            ORDER BY c.status, u.user_zone, u.user_name, u.user_address ASC"; 

    
    $phaseData= $data->selectJoinAll($query);
    $c=$phaseData->count();
    if(!empty($phaseData) && $c > 0){
        print_r(json_encode($phaseData->results()));        
    }else{                
        echo 0;
    }        

}
?>