<?php 
include_once 'core/init.php';
$data = new User();

?>
<style>
    table, tr, th, td{
        border:1px solid gray;
        border-collapse:collapse;
    }
    th, td{
        padding:3px 5px;
    }
</style>

                    <table >
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Phase</th>
                                <th>Zone</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Timing</th>
                                <th>last Communication</th>
                                <th>Totalizer Reset</th>
                                <th>PLC Reset</th>
                                <th>Panel Lock</th>
                                <th>Limit</th>
                                <th>Received Limit</th>
                                <th>Today Flow</th>
                                <th>Output</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <?php   
                                    $u_dat = $data->selectJoinAll('SELECT u.*, l.* FROM sd_user u LEFT JOIN sd_live_panel_status l ON l.user_id = u.user_id ORDER BY user_location, user_zone ASC');
                                    $c=$u_dat->count();

     
                                    for ($i=0; $i<$c; $i++) {
                                       

                                            ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td>Phase <?php echo $u_dat->results()[$i]->user_location; ?></td>
                                <td>Zone <?php echo $u_dat->results()[$i]->user_zone; ?></td>
                                <td><?php echo $u_dat->results()[$i]->user_name; ?></td>
                                <td><?php echo $u_dat->results()[$i]->user_address; ?></td>
                                <td><?php echo $u_dat->results()[$i]->panel_unlock_timing; ?></td>
                                <td><?php echo $u_dat->results()[$i]->date_time; ?></td>
                                <td>
                                <?php if($u_dat->results()[$i]->user_totalizer_reset == 0){ echo 'OFF'; }else{ echo 'ON';} ?>
                                </td>
                                <td>
                                <?php if($u_dat->results()[$i]->user_plc_reset == 0){ echo 'OFF'; }else{ echo 'ON';} ?>
                                </td>
                                <td>
                                <?php if($u_dat->results()[$i]->user_panel_lock == 0){ echo 'Un-Lock'; }else{ echo 'Lock';} ?>
                                </td>
                                <td>
                                <?php echo $u_dat->results()[$i]->user_limit; ?>
                                </td>
                                <td>
                                <?php echo $u_dat->results()[$i]->kld_limit_send; ?>
                                </td>
                                <td>
                                <?php echo $u_dat->results()[$i]->today_flow; ?>
                                </td>
                                <td>
                                <?php echo $u_dat->results()[$i]->pipe_size; ?>
                                </td>

                            </tr>
                            <?php
                                        
                                    }
                                
                            ?>
                             
                        </tbody>
                    </table>






