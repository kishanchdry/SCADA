<?php
  
$servername = "localhost";
$username = "root";
$password = "V*ision*123";

try {
  $conn = new PDO("mysql:host=$servername;dbname=scada", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

$query ='Set Global max_connections=2000';
$conn->query($query);

  date_default_timezone_set("Asia/Calcutta");
 
  if(isset($_GET['ID'])){
    
    //$user_id = $_GET['user_id'];
	  $panel_no = $_GET['ID'];
    $emergencyStop =$_GET['A'];
    $door_limit_switch =$_GET['B'];
    $current_flow =$_GET['C'];
    $totalizer =$_GET['D'];
    $overload =$_GET['E'];
    $pump_on_off =$_GET['F'];
    $level =$_GET['G'];
    $auto_manual =$_GET['H'];
    $panel_lock =$_GET['I'];
    //not added
    $today_flow =$_GET['J'];
    //**** */
    $kld_limit_send =$_GET['K'];
    $pipe_size =$_GET['L'];
    $rtc_dd =$_GET['M'];
    $rtc_hh =$_GET['N'];
    $rtc_mm =$_GET['O'];
    // $fc_50_signal =$_GET['P'];
    // $fc_50_value =$_GET['Q'];
	
	//Getting the userid of system
	$get_user_id = "SELECT  * FROM sd_user  WHERE user_panel_no=".$panel_no;
  $user_id = $conn->query($get_user_id);
 
  $user_id->execute();
  $row = $user_id->fetch();
  $user_id = $row['user_id'];
  $panel_unlock_timing = $row['panel_unlock_timing'];
  $panel_lock_status =  $row['user_panel_lock'];
  $phase = $row['user_location'];



 

    $date_time = date("Y-m-d H:i:s");
    $query  = "UPDATE sd_live_panel_status ";
    $query .= "SET emergency_stop={$emergencyStop}, ";
    $query .= "door_limit_switch={$door_limit_switch}, ";
    $query .= "c_flow=({$current_flow}/3600)*1000, ";
    $query .= "totalizer={$totalizer}/1000, ";
    $query .= "overload={$overload}, ";
    $query .= "pump_status={$pump_on_off}, ";
    $query .= "tank_level={$level}, ";
    $query .= "auto_manual={$auto_manual}, ";
    $query .= "panel_lock={$panel_lock}, ";
    $query .= "today_flow={$today_flow}/1000, ";
    $query .= "kld_limit_send={$kld_limit_send}/1000, ";
    $query .= "pipe_size={$pipe_size}, ";
    $query .= "rtc_dd={$rtc_dd}, ";
    $query .= "rtc_hh={$rtc_hh}, ";
    $query .= "rtc_mm={$rtc_mm} ";
    //$query .= "date_time = now() ";
   // $query .= "date_time = { $date_time}";
    // $query .= "fc_signal={$fc_50_signal}, ";
    // $query .= "fc_value={$fc_50_value} ";
    $query .= "WHERE user_id = {$user_id} ";

    $update_data = $conn->query($query);
   
    if(!$update_data){
      die("Query Failed 1".mysqli_error($conn));
    }
    
    
    // $update_time_zone = "SET time_zone = '+05:30'";
    // $conn->query($update_time_zone);
	
    //Updating the timestamp of the current field
     $update_time = "UPDATE sd_live_panel_status SET date_time = now() WHERE user_id = {$user_id}";
    $update_single_time = $conn->query($update_time);
   
    //Sending data to the last 7 days 
    // $query = "INSERT INTO last_7days_data(user_id,totalizer,date_time) VALUES ({$user_id}, {$totalizer}, now()) ";
    // $inser_data_7days = $conn->query($query);
    
    // if(!$inser_data_7days){
    //   die("Query Failed".mysqli_error($conn));
    // }

    //session_start();

    try{

      $con=new PDO('mysql:host=localhost;dbname=scadalast7daysdata', 'root', 'V*ision*123');
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
       $minute =date('i');
      
      
      $day=date('Y_m_d');
      $table = 'day_'.$day;

      $createTable ='CREATE TABLE IF NOT EXISTS '.$table.' (
        `id` int(11) NOT NULL auto_increment,
        `user_id` int(11) NOT NULL,
        `totalizer` float NOT NULL,
        `panel_status` int(5) NOT NULL,
        `date_time` datetime NOT NULL,
        PRIMARY KEY (`id`),
        INDEX uid (user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
      $con->exec($createTable);


      $mi ='SELECT * FROM sd_user WHERE  user_id ='.$user_id;
      $minu = $conn->query($mi);
      $minu->setFetchMode(PDO::FETCH_ASSOC);
      $db_minute = $minu->fetchAll();

     if($minute != $db_minute[0]['minute'] ){
        $dayData = 'INSERT INTO '.$table.' (user_id, totalizer, panel_status, date_time) VALUES ('.$user_id.', '.$totalizer.', '.$panel_lock.',"'.$date_time.'")';
        $con->exec($dayData);

        $up_m = 'UPDATE sd_user SET minute ='.$minute.' WHERE user_id = '.$user_id;
        $conn->query($up_m);
     }

     
   
     
      // $sql = 'INSERT INTO last_7days_data (user_id, totalizer, date_time) VALUES ('.$user_id.', '.$totalizer.', "'.$date_time.'")';
      // $con->exec($sql);



    }catch(PDOException $e){
      die($e->getMessage());
    }
       

    

    //Updating the Date time into the comm_status table
    $query = "UPDATE comm_status ";
    $query .= "SET date_time = now(), status=1 ";
    $query .= "WHERE user_id={$user_id} ";
    $update_date_time = $conn->query($query);
   
    // Sending data to the devices
    $query = "SELECT * FROM sd_user WHERE user_id = {$user_id} ";
    $get_data_to_send = $conn->query($query);
   
    if(!$get_data_to_send){
      die("Query Failed".mysqli_error($conn));
    }
    $get_data_to_send->setFetchMode(PDO::FETCH_ASSOC);
    $data = $get_data_to_send->fetchAll();
    foreach($data as $row){
      $zero=0;
      $num =$row['user_limit'];
      $str_arr = explode('.',$num);
      $numlength=strlen((string)$str_arr[0]);  // Before the Decimal point

      
      // $num=25;
      //$numlength = strlen((string)$num);
      echo $row['user_mode'];//1 or 0
      echo $row['user_panel_lock'];// 1 or 0
	  echo ",";
	  if($numlength == 1){
		echo $zero;
		echo $zero;
		echo $num*1000;//9*1000=009000
	  }		
      if($numlength == 2){
        echo $zero;
        echo $num*1000;//090000
      }else if($numlength > 2){
        echo $num*1000;
      }//10,090000
      echo ",";
      echo $row['user_plc_reset'];//0 or 1
      echo ",";
      echo $row['user_reset_totalizer'];// 0 or 1
      echo ",";
      echo "1";
      echo ",";
      //Single and double character of date 
      $dd = date("d");
      $dd_length = strlen((string)$dd);
      if($dd_length == 2){
        echo $dd;
      }else if($dd_length == 1){
        echo $zero;
        echo $dd;//09
      }
      echo "/";
      $hh = date("h");
      $hh_length = strlen((string)$dd);
      if($hh_length == 2){
        echo $hh;
      }else if($hh_length == 1){
        echo $zero;
        echo $hh;
      }
      echo "/";
      $dd = date("i");
      $dd_length = strlen((string)$dd);
      if($dd_length == 2){
        echo $dd;
      }else if($dd_length == 1){
        echo $zero;
        echo $dd;
      }
      echo ",";
      echo 0;
	  
    }


  // $date_time=date('Y-m-d H:i:s');

  // $qud="SELECT * FROM alarm WHERE user_id={$user_id} AND a_name='Door' ORDER BY a_id DESC LIMIT 1";
  // $d_data=$conn->query($query);

 
  // $d_data->setFetchMode(PDO::FETCH_ASSOC);
  // $door_alarm_data = $d_data->fetchAll();
  // if($door_alarm_data['a_state_on'] ==1 && $door_alarm_data['a_state_off'] ==1 && $door_limit_switch==1){
  //     $query="INSERT INTO `alarm`(`a_name`, `user_id`, `a_state_on`, `a_date_time_on`) 
  //             VALUES('Door', {$user_id},1,'$date_time')";
  //     $get=$conn->query($query);
      
  // }
  // else if($door_alarm_data['a_state_on'] ==1 && $door_alarm_data['a_state_off'] ==0 &&  $door_limit_switch==0){
    
  //    $query="UPDATE `alarm` SET `a_state_off`=1, `a_date_time_off`='$date_time' WHERE a_id={$door_alarm_data['a_id']}";
  //    $get=$conn->query($query);
     
  // }else if(empty($door_alarm_data['a_state_on']) && empty($door_alarm_data['a_state_off']) && $door_limit_switch==1){
  //   $query="INSERT INTO `alarm`(`a_name`, `user_id`, `a_state_on`, `a_date_time_on`) 
  //   VALUES('Door', {$user_id},1,'$date_time')";
  //   $get=$conn->query($query);
  // }


  // $quf="SELECT * FROM alarm WHERE user_id={$user_id} AND a_name='FC_50' ORDER BY a_id DESC LIMIT 1";
  // $f_data=$conn->query($query);
 
  // $f_data->setFetchMode(PDO::FETCH_ASSOC);
  // $FC_50_alarm_data = $f_data->fetchAll();
  // if($FC_50_alarm_data['a_state_on'] ==1 && $FC_50_alarm_data['a_state_off'] ==1 && $fc_50_signal==0){
  //     $query="INSERT INTO `alarm`(`a_name`, `user_id`, `a_state_on`, `a_date_time_on`) 
  //             VALUES('FC_50', {$user_id},1,'$date_time')";
  //     $get=$conn->query($query);

      
  // }
  // else if($FC_50_alarm_data['a_state_on'] ==1 && $FC_50_alarm_data['a_state_off'] ==0  && $fc_50_signal==1){
  //     $a_id = $_SESSION[$fc_50_id_session];
  //     $query="UPDATE `alarm` SET `a_state_off`=1, `a_date_time_off`='$date_time' WHERE a_id={$FC_50_alarm_data['a_id']} ";
  //     $get=$conn->query($query);
      
  // }else if(empty($FC_50_alarm_data['a_state_on']) && empty($doorFC_50_alarm_data_alarm_data['a_state_off']) && $fc_50_signal==0){
  //   $query="INSERT INTO `alarm`(`a_name`, `user_id`, `a_state_on`, `a_date_time_on`) 
  //   VALUES('FC_50', {$user_id},1,'$date_time')";
  //   $get=$conn->query($query);
  // }


//// 

  // $query = "SELECT * FROM sd_user WHERE user_id NOT IN (SELECT id FROM `start_totalizer`)";
  // $select_data_sd_user = $conn->query($query);
  // $select_data_sd_user->setFetchMode(PDO::FETCH_ASSOC);
  // $data = $select_data_sd_user->fetchAll();
 
  //   foreach($data as $row){
      
  //     if ($row['user_id'] !=2) {
  //         $query = "INSERT INTO start_totalizer (id,tdate, m_totalizer) VALUES('".$row['user_id']."',Date(now()),0.00) ";
  //         $insert_into_comm_status = $conn->query($query);
  //         if (!$insert_into_comm_status) {
  //             die("Query Failed4" . mysqli_error($conn));
  //             $error=4;
  //         }
          
  //     }
    
  // }

  // $query = "SELECT * FROM panel_lock_shedule";
  // $sheduled_data = $conn->query($query);
  // $sheduled_data->setFetchMode(PDO::FETCH_ASSOC);
  // $sheduled_data = $sheduled_data->fetchAll();

  // //$sheduled_data= $data->selectAll('panel_lock_shedule');

  // if(!empty($sheduled_data)){

  //   $start_time = strtotime(date('Y-m-d '.$sheduled_data->results()[0]->start));
  //   $start_hrs = date('H', $start_time); 
  //   $start_min = date('i', $start_time); 
  //   $end_time = strtotime(date('Y-m-d '.$sheduled_data->results()[0]->end));
  //   $end_hrs = date('H', $end_time); 
  //   $end_min = date('i', $end_time); 
  //   $get_current_hrs = date('H');
  //   $get_current_min = date('i');

  //   if($get_current_hrs >= $start_hrs && $get_current_min >= $start_min && $get_current_hrs <= $end_hrs && $get_current_min <= $end_min){
  //     $query="UPDATE sd_user SET user_panel_lock = 0 WHERE panel_lock_shedule_mode = 0 ";
  //     $conn->query($query);
    
  //   }else{
  //     $query="UPDATE sd_user SET user_panel_lock = 1 WHERE panel_lock_shedule_mode = 0 ";
  //     $conn->query($query);
     
  //   }

  // }

// panel autolock based on timing
  $current_hrs = date('H');
  $timing = $panel_unlock_timing;
  $timing = str_replace('-',',', $timing);
  $timing = explode(',', $timing);
   //if($user_id == 9 || $user_id == 21 || $user_id == 310 || $user_id == 15 || $user_id == 24 || $user_id == 311 || $user_id == 302 || $user_id == 22){
  //if($user_id == 9 ){

    $current_hrs = (int)$current_hrs;
    $timing1 =(int)$timing[0];
    $timing2 =(int)$timing[1];
    $timing3 =(int)$timing[2];
    $timing4 =(int)$timing[3];

   // if(count($timing) > 4 ){

      $timing5 =(int)$timing[4];
      $timing6 =(int)$timing[5];

    //   if($current_hrs >= $timing5 && $current_hrs < $timing6 || $current_hrs >= $timing5 &&  $current_hrs > $timing6 && $timing6 == 00 ){

    //     $query="UPDATE sd_user SET user_panel_lock = 0 WHERE panel_lock_shedule_mode = 1 AND user_id =".$user_id;
    //     $conn->query($query);  

    //   }else{

    //     $query="UPDATE sd_user SET user_panel_lock = 1 WHERE panel_lock_shedule_mode = 1 AND user_id =".$user_id;
    //     $conn->query($query);
      
    //   }

    // }
  
  if( $user_id != 172){
      if($current_hrs >= $timing1 && $current_hrs < $timing2 ){

       	$query="UPDATE sd_user SET user_panel_lock = 0 WHERE panel_lock_shedule_mode = 1 AND user_id =".$user_id;
       	$conn->query($query);  

      }else if($current_hrs >= $timing3 && $current_hrs < $timing4 ){

        $query="UPDATE sd_user SET user_panel_lock = 0 WHERE panel_lock_shedule_mode = 1 AND user_id =".$user_id;
        $conn->query($query);  

      }
      // else 
      // if($current_hrs >= $timing5 && $current_hrs < $timing6 || $current_hrs >= $timing5  && $timing6 == 00 ){
     

      //   $query="UPDATE sd_user SET user_panel_lock = 0 WHERE panel_lock_shedule_mode = 1 AND user_id =".$user_id;
      //   $conn->query($query);  

      // }
      else 
      {

        $query="UPDATE sd_user SET user_panel_lock = 1 WHERE panel_lock_shedule_mode = 1 AND user_id =".$user_id;
        $conn->query($query);
      
      }
      
     
}
// }


     //// totalizer bit on - off
// if($user_id == 9){
//      $date01 = date('d');
//      $datehrs = date('H');
 
//      if($date01 == 22 || $date01 == 22 AND $user_id == 9  AND $datehrs >= 14  AND $datehrs < 15){
//         $query="UPDATE sd_user SET user_reset_totalizer = 1 WHERE user_id =".$user_id;
//         $conn->query($query);
//      }else{
//         $query="UPDATE sd_user SET user_reset_totalizer = 0 WHERE user_id =".$user_id;
//       $conn->query($query);
//      }
//     }

  $quer= "SET GLOBAL event_scheduler='ON'";
  $conn->query($quer);
  
  }

  

?>
