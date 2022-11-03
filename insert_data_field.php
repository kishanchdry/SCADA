<?php
include("core/init.php");
 
 //include("update-functions.php");
 
 $calculations = new Calculations();

  $data = new User();

  if(isset($_GET['ID'])){
   
    //$user_id = $_GET['user_id'];
	  $panel_no = $_GET['ID'];
    $emergencyStop =$_GET['A'];
    $door_limit_switch =$_GET['B'];
    $current_flow =$_GET['C'];
    $totalizer =(strlen((string)$_GET['D']) > 10) ? 0 : $_GET['D'];
    $overload =$_GET['E'];
    $pump_on_off =$_GET['F'];
    $level =$_GET['G'];
    $auto_manual =$_GET['H'];
    $panel_lock =$_GET['I'];
    //not added
    $today_flow =$_GET['J'];
    //**** */

    // $kld_limit_send =(isset($_GET['K'])) ? $_GET['K'] : 'K';
    // $pipe_size =(isset($_GET['L'])) ? $_GET['L'] : 'L';
    // $rtc_dd =(isset($_GET['M'])) ? $_GET['M'] : 'M';
    // $rtc_hh =(isset($_GET['N'])) ? $_GET['N'] : 'N';
    // $rtc_mm =(isset($_GET['O'])) ? $_GET['O'] : 'O';

    $kld_limit_send =$_GET['K'];
    $pipe_size =$_GET['L'];
    $rtc_dd =$_GET['M'];
    $rtc_hh =$_GET['N'];
    $rtc_mm =$_GET['O'];
    
    if(isset($_GET['P'])){
      $soft_php =  $_GET['P'];
    }else{
      $soft_php =  0;
    }
	
	//Getting tdata of unit by panel number
  $get_data =$data->getData('sd_user', array('user_panel_no', '=', $panel_no)); 
  //if(empty($get_data)){
    $user_data = $get_data->results()[0];
  //} 
  
  $user_id = $user_data->user_id;
  $panel_lock_shedule_mode = $user_data->panel_lock_shedule_mode;
  $panel_unlock_timing = $user_data->panel_unlock_timing;
  $panel_unlock_dates = $user_data->panel_unlock_dates;
  
      //////   ECHO STRING //////////////////////////////////////////////////////
      

  $limit_fetch = $calculations->limitLength($user_data->user_limit);
  $dd = $calculations->DDHHMMLength(date("d"));
  $hh = $calculations->DDHHMMLength(date("h"));
  $mm = $calculations->DDHHMMLength(date("i"));
  
  echo  $user_data->user_mode.''.$user_data->user_panel_lock.','.$limit_fetch.','.$user_data->user_plc_reset.','.$user_data->user_reset_totalizer.',1,'.$dd.'/'.$hh.'/'.$mm.',0';


    ////////  ECHO STRING ////////////////////////////////////////////////////

  
    /// Query for db timezone set +5:30

  $set_db_time_zone ='SET time_zone = "+05:30"'; 
  $data->selectJoinAll($set_db_time_zone);   

 //////   update comm_status  data  ///////////////////////////////////

  $data->updateData('comm_status', $user_id, 'user_id', array(
      'date_time' => date('Y-m-d H:i:s'),
      'status' => 1
  ));

 //////   update comm_status  data  ///////////////////////////////////

  $qtr = 'UPDATE sd_user SET soft_php= '.$soft_php.' WHERE user_id ='.$user_id;
  $data->selectJoinAll($qtr);
  


//////   update live status data  ///////////////////////////////////

  $current_flow = ($current_flow/3600)*1000;
  $totalizer = $totalizer/1000;
  $today_flow =  $today_flow/1000;
  $kld_limit_send = (INT)$kld_limit_send/1000;

  $Query = " UPDATE sd_live_panel_status SET 
      emergency_stop = $emergencyStop,
      door_limit_switch= $door_limit_switch,
      c_flow = $current_flow,
      totalizer = $totalizer,
      overload = $overload,
      pump_status = $pump_on_off,
      tank_level = $level,
      auto_manual = $auto_manual,
      panel_lock = $panel_lock,
      today_flow = $today_flow,
      kld_limit_send = $kld_limit_send,
      pipe_size = $pipe_size,
      rtc_dd = $rtc_dd,
      rtc_hh = $rtc_hh,
      rtc_mm  = $rtc_mm,
      date_time = now() WHERE user_id = ".$user_id;     

    $data->selectJoinAll($Query);   

//////   llast7days data  ///////////////////////////////////


try{
  
  $db_con=new PDO('mysql:host=3.7.237.55;dbname=scadalast7daysdata', 'root', '4iTgl2PPONx88jM');
  $db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $current_minute =date('i');
 
  
  $day=date('Y_m_d');

  $table = 'day_'.$day;

  $createTable ='CREATE TABLE IF NOT EXISTS '.$table.' (
    `id` int(11) NOT NULL auto_increment,
    `user_id` int(11) NOT NULL,
    `totalizer` float NOT NULL,
    `panel_status` int(11) NOT NULL,
    `date_time` datetime NOT NULL,
    PRIMARY KEY (`id`),
    INDEX uid (user_id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
  
  $db_con->exec($createTable);
  $db_minute = $user_data->minute;
  if($current_minute !=  $db_minute ){
      
    $dayData = 'INSERT INTO '.$table.' (user_id, totalizer, date_time, panel_status) VALUES ('.$user_id.', '.$totalizer.', now(),'.$panel_lock.')';
    $db_con->exec($dayData);

    
    $data->updateData('sd_user', $user_id, 'user_id', array('minute' => $current_minute));

  }    
     
}catch(PDOException $e){
  die($e->getMessage());
}
    
$shedule_mode = $data->selectAll('shedule_panel_lock_mode');


  $shedule_status = $shedule_mode->results()[0]->status;
  $shedule_times = $shedule_mode->results()[0]->times;
  $shedule_dates = $shedule_mode->results()[0]->dates;


 /// manual panel lock according time
// if($shedule_status == 0){
//   $sheduled_data= $data->selectAll('panel_lock_shedule');
//   if(!empty($sheduled_data)){
//     $sheduled = true;
//     $startTime = $sheduled_data->results()[0]->start;
//     $endTime = $sheduled_data->results()[0]->end;
//     $manual_panel_status = $calculations->manualShedulePanellock($user_id, $sheduled, $startTime, $endTime);
//     $data->updateData('sd_user', $user_id, 'user_id', array('user_panel_lock' => $manual_panel_status));
//   }
// }

/// auto panel lock according time
if($shedule_status == 1){
  if($shedule_times == 1 && $shedule_dates == 0){
    $auto_time_panel_status = $calculations->autoTimesShedulePanelLock($panel_unlock_timing);
    $data->updateData('sd_user', $user_id, 'user_id', array('user_panel_lock' => $auto_time_panel_status));
  }else if($shedule_dates == 1 && $shedule_times == 0){
    $auto_date_panel_status = $calculations->autoDatesShedulePanelLock($panel_unlock_dates);
    $data->updateData('sd_user', $user_id, 'user_id', array('user_panel_lock' => $auto_date_panel_status));
  }
  
}

//  database event sedule on

  $event_shedule_on = "SET GLOBAL event_scheduler='ON'";  //  database event sedule on
  $data->selectJoinAll($event_shedule_on);
  
/// For monthely report query set max length for query words  
  $max_connections ='Set Global max_connections=2000';  
  $data->selectJoinAll($max_connections);



}



?>
