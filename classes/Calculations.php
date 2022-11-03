<?php 

class Calculations{
    
    public function limitLength($limit){
        $limit_arr = explode('.',$limit);
        $limitLength=strlen((string)$limit_arr[0]);

        if($limitLength == 1){
            return '00'.$limit*1000;//9*1000=009000
        }else if($limitLength == 2){
            return '0'.$limit*1000;//9*1000=009000
        }else if($limitLength > 2){
            return $limit*1000;//9*1000=009000
        }//10,090000
    }

 ///////// day-time-minute for string  

    public function DDHHMMLength($value){
        $value_length = strlen((string)$value);
        if($value_length < 2){
        return '0'.$value;
        }else{
            return $value;
        }
    }


    ///////// panel manual lock based on timing  

    public function manualShedulePanellock($user_id, $sheduled, $startTime, $endTime){

        if($sheduled){

            $start_time = strtotime(date('Y-m-d '.$startTime));
            $start_hrs = date('H', $start_time); 
            $start_min = date('i', $start_time); 
            $end_time = strtotime(date('Y-m-d '.$endTime));
            $end_hrs = date('H', $end_time); 
            $end_min = date('i', $end_time); 
            $get_current_hrs = date('H');
            $get_current_min = date('i');

            if($get_current_hrs >= $start_hrs && $get_current_min >= $start_min && $get_current_hrs <= $end_hrs && $get_current_min <= $end_min){
            
            return 1;
            
            }else{

                return 0;
            
            }

        }

    }

    ///////// panel autolock based on timing  

    public function autoTimesShedulePanelLock($timing){
    

        
        $current_hrs = date('H');
        $timing = str_replace('-',',', $timing);
        $timing = str_replace('00','24', $timing);
        $timing = explode(',', $timing);
        $status=0;
        $current_hrs = (int)$current_hrs;
    
        $time_count = count($timing);
    
        for($i=0;$i<$time_count;$i+=2){
    
            $firstTime = $i;
            $secondTime = $i+1;
           
                if($current_hrs >= $timing[$firstTime] && $current_hrs < $timing[$secondTime]){
                    
                $status +=0;
        
                }else{
        
                $status +=1;
                
                } 
           

          
        } 
        
        if($status == ($time_count/2)){
            return 1;
        }else if($status < ($time_count/2)){
            return 0;
        }else{
            return 1;
        }
        
    }


      ///////// panel autolock based on dates  

      public function autoDatesShedulePanelLock($dates){
    
        
        $current_day = date('d');
        $dates = explode(',', $dates);
        $days = count($dates);
    
        for($i=0;$i<$days;$i++){
    
    
            if($current_day == $dates[$i]){
                
             $status +=0;
    
            }else{
    
             $status +=1;
            
            } 
    
        } 
        
        if($status == $days){
            return 1;
        }else if($status < $days){
            return 0;
        }else{
            return 1;
        }
    
    
        
    }

    /////////  com_status update for today offline (0) and yesterday offline (2)

    public function commStatusUpdate($dbDateTime){
      
        $TimeDifference = strtotime("now") - strtotime($dbDateTime);      
        if($TimeDifference > 900 && $TimeDifference < 86400){
            return 0;
        }else if($TimeDifference < 900){
            return 1;
        }else if( $TimeDifference > 86400){
            return 2;
        }
    }










  ///////// alarms /////////////////////////////





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





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


}