<?php 


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
            $phaseid=$_POST['phase'];
            $z_query ="SELECT 
                    u.user_name, 
                    u.user_id, 
                    u.user_zone,
                    u.user_address,
                    u.user_limit,
                    u.user_today_limit,
                    c.status,
                    c.date_time,
                    lp.overload,
                    lp.auto_manual,
                    lp.c_flow,
                    lp.today_flow,
                    lp.panel_lock,
                    u.user_panel_lock,
                    t.tdate,
                    round(sum(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 1 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t1, 
                    round(sum(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 2 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t2, 
                    round(sum(CASE WHEN t.tdate = DATE_ADD(CURDATE(),INTERVAL - 3 DAY) THEN t.totalizer_diff ELSE 0 END), 2) as t3 
                    
                from 
                    
                    sd_user u 
                INNER JOIN
                    comm_status c ON c.user_id = u.user_id
                INNER JOIN 
                    sd_live_panel_status lp ON lp.user_id = u.user_id
                LEFT JOIN
                    tester t ON t.id= u.user_id 
                WHERE
                    u.user_location =".$phaseid." 
                GROUP BY 
                    u.user_name, u.user_id, u.user_address, u.user_limit, c.date_time, lp.overload, lp.auto_manual, lp.c_flow, lp.today_flow, lp.panel_lock 
                    ORDER BY c.status ASC, u.user_zone ASC, u.user_address ASC";

            $zoneData= $data->selectJoinAll($z_query);
            $c=$zoneData->count();
            $i=1;
            for($r=0; $r<$c; $r++){
           
				
				
        ?>
        <tr class="<?php if($zoneData->results()[$r]->status == 1){echo "text-success ";}else if($zoneData->results()[$r]->status == 0){echo "text-warning";}else if($zoneData->results()[$r]->status == 2){echo "text-danger";}else if($zoneData->results()[$r]->status == 3){echo "text-primary";}?>">
            <td> <?php echo $i; ?></td>
            <td class="text-center"><?php $t_limit = ($zoneData->results()[$r]->user_limit * $zoneData->results()[$r]->user_today_limit /100 );  $t_flow = floatval($zoneData->results()[$r]->today_flow); if($t_flow>$t_limit){?><i
                    class='fas fa-exclamation-circle text-danger'></i><?php } else {?><i
                    class='fas fa-circle text-success'></i><?php } ?></td>
            <td class="text-center">
               
            </td>
            <td class="text-center">
                <?php if($zoneData->results()[$r]->c_flow > 0 ){ ?>

                <div class="round">
                    <div id="cta">
                        <span class="arrow primera next "></span>
                        <span class="arrow segunda next "></span>
                    </div>
                </div>
                <?php }  ?>
            </td>
            <td><a href="unit-profile.php?u=<?php echo $zoneData->results()[$r]->user_id;?>"><?php echo $zoneData->results()[$r]->user_name;?></a></td>
            <td>Zone <?php echo $zoneData->results()[$r]->user_zone;?></td>
            <td id="<?php echo $zoneData->results()[$r]->status; ?>" class="gr today"><i
                    class='fas fa-circle '></td>
            <td class="gr"><?php echo $zoneData->results()[$r]->date_time;?></td>
            <td class="gr"><?php echo $zoneData->results()[$r]->user_address;?></td>
            <td class="gr"><?php echo $zoneData->results()[$r]->user_limit;?></td>
            <td class="gr"><?php echo $zoneData->results()[$r]->c_flow;?></td>
            <td class="gr"><?php echo $zoneData->results()[$r]->today_flow?></td>            
            <td class="gr"><?php if($zoneData->results()[$r]->t1==1.00){ echo '-';}else{ echo $zoneData->results()[$r]->t1; }?></td>
            <td class="gr"><?php if($zoneData->results()[$r]->t2==1.00){ echo '-';}else{ echo $zoneData->results()[$r]->t2; }?></td>
            <td class="gr"><?php if($zoneData->results()[$r]->t3==1.00){ echo '-';}else{ echo $zoneData->results()[$r]->t3; }?></td>
           
            <td><?php if($zoneData->results()[$r]->overload == 1 ){?><i
                    class='fas fa-times-circle text-danger'></i><?php } else {?><i
                    class='fa fa-check-circle text-success'></i><?php }?></td>
            <td><?php if($zoneData->results()[$r]->auto_manual == 0 ){ echo "M"; } else { echo "A"; }?></td>
            <td><?php if($zoneData->results()[$r]->panel_lock == 1 ){?><i class='fas fa-lock text-danger'></i><?php } else {?><i
                    class='fas fa-lock-open text-success'></i><?php }?></td>
        </tr>
        <?php
                 $i++;
                }
               
           
   
            }

            ?>