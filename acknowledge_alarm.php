<?php 
include('core/init.php');

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    $data = new User();
	    $a_id=$_POST['a_id'];
        $end=date('Y-m-d H:i:s');
        $result= $data->updateData('alarm', $a_id, 'a_id', array(
            'a_state_off'=>1, 
            'a_acknoledge' => 1, 
            'a_date_time_off'=> $end
        )); 

        echo $result;
   
}
?>