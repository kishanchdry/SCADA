
<?php 
include('core/init.php');

$data = new User();
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
	 $u_id=$_POST['user_id'];
		$alarm = $data->getData('alarm', array('user_id', '=', $u_id));
        if(!empty($alarm)){
        $c=$alarm->count();
        for($i=0;$i<$c;$i++){

            if ($alarm->results()[$i]->a_state == 0 && $alarm->results()[$i]->a_acknoledge == 0 && date('Y-m-d', strtotime($alarm->results()[$i]->a_date_time_on))  == date('Y-m-d')) {
                ?>
        <tr>
            <td ><?php echo $alarm->results()[$i]->a_name; ?></td>
            <td ><?php echo $alarm->results()[$i]->a_date_time_on; ?></td>
            <td ><?php echo $alarm->results()[$i]->a_date_time_off; ?></td>
            <td class="text-danger"><?php if($alarm->results()[$i]->a_state==0) { ?><i class="fa fa-bell text-danger" ></i> <?php } ?></td>
            <td ><i onclick="acknowledge(<?php echo $alarm->results()[$i]->a_id; ?>)" class="cursor fas fa-check-circle text-info"></i></td>
        </tr>
    <?php
        }
    }
}
}
?>