<?php 


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

    include ('core/init.php');
    $data = new User();    
           
  
    $phaseid=$_POST['phase_id'];
    $z_query ="SELECT * FROM sd_industrial_zone z, sd_industrial_phase_zone pz where z.zone_id = pz.zone_id and pz.phase_id = ".$phaseid;

    $zoneData= $data->selectJoinAll($z_query);
    $c= $zoneData->count();
   
    for ($i=0; $i<$c; $i++) {
      
        ?>
        <tr>
            <td><?php echo $s=$i+1; ?></td>
            <td><?php echo   $zoneData->results()[$i]->zone_name; ?></td>
            <td>
                <div class="input-group mb-3">
                <input type="number" min="0" max="100"  class="form-control"
                        value="<?php echo   $zoneData->results()[$i]->zone_limit; ?>" id="zone_limit<?php echo   $zoneData->results()[$i]->zone_id; ?>" />
                    <div class="input-group-prepend">
                        <span class="input-group-text">%</span>
                    </div>
                    
                </div>
            </td>
            <td class="text-center"><input type="checkbox" id="zone_state<?php echo   $zoneData->results()[$i]->zone_id; ?>" <?php if (  $zoneData->results()[$i]->zone_state==1) {
                                echo 'checked';
                            } ?> /></td>
            <td class="text-center"><input type="hidden" id="phaseid<?php echo   $zoneData->results()[$i]->zone_id; ?>"
                    value="<?php echo $phaseid;?>" />
                <input type="checkbox" <?php if (  $zoneData->results()[$i]->zone_plc_reset==1) {
                                echo 'checked';
                            } ?> id="zone_plc_reset<?php echo   $zoneData->results()[$i]->zone_id; ?>" /></td>
            <td class="text-center"><a href=" #" onclick="updateZone(<?php echo   $zoneData->results()[$i]->zone_id; ?>)" class="text-info"><i
                        class="fa fa-edit"></i></a></td>
           
        </tr>
        <?php 
    }
}

?>