<?php 
include_once 'includes/header.php';
$db = new User();

$db->selectJoinAll('DELETE FROM offlineunits WHERE unitID IN (SELECT user_id FROM comm_status WHERE status = 1)');

$db->selectJoinAll('INSERT INTO offlineunits (`unitID`, `name`, `zone`, `location`, `address`, `reson`, `offlineDateTime`) SELECT c.user_id, u.user_name, c.zone_id, p.phase_name, u.user_address, 0, c.date_time FROM comm_status c LEFT JOIN sd_user u ON c.user_id = u.user_id LEFT JOIN sd_industrial_phase p ON c.phase_id = p.phase_id WHERE c.status != 1 AND c.user_id NOT IN (SELECT unitID FROM offlineunits)');

$offlineUnits = $db->selectJoinAll('SELECT * FROM offlineunits ORDER BY name, location, zone, address');

//$offlineUnits = $db->selectJoinAll('SELECT c.user_id, u.user_name, c.zone_id, p.phase_name, u.user_address, 0, c.date_time FROM comm_status c LEFT JOIN sd_user u ON c.user_id = u.user_id LEFT JOIN sd_industrial_phase p ON c.phase_id = p.phase_id WHERE c.status != 1');

?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Offline Units </a></p>
</div>


<div class="row" id='top'>    
    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-6">
                        <h6 class="m-0 font-weight-bold text-primary">Offline Units </h6>
                    </div>
                    <div class="col-xl-6 text-right">
                        <button onclick="export_excel('Table')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-excel"></i>
                        </button>

                        <button title="Export to PDF" onclick="getPDF('Table')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <form method="post" >                                          
                <div class="table-responsive">
                <table class="table table-bordered table-sm" id="Table" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Area</th>
                                <th>Zone</th>
                                <th>Address</th>
                                <th>Reason</th>
                                <th>Offline Date-Time </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!empty($offlineUnits)){
                                    $ou = $offlineUnits->count();
                                    for($i=0;$i<$ou;$i++){
                                        ?>

                                            <tr>
                                                <td><?=$i+1?></td>
                                                <td><?=$offlineUnits->results()[$i]->name?></td>
                                                <td><?=$offlineUnits->results()[$i]->location?></td>
                                                <td>Zone <?=$offlineUnits->results()[$i]->zone?></td>
                                                <td><?=$offlineUnits->results()[$i]->address?></td>
                                                <td>
                                                    <select class='editable' id="<?=$offlineUnits->results()[$i]->id?>">
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 0){echo 'selected';} ?> value='0'> N/A </option>
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 1){echo 'selected';} ?> value='1'> Controller </option>
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 2){echo 'selected';} ?> value='2'> GSM </option>
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 3){echo 'selected';} ?> value='3'> SMPS </option>
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 4){echo 'selected';} ?> value='4'> Contactor </option>
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 5){echo 'selected';} ?> value='5'> Closed </option>
                                                        <option <?php if($offlineUnits->results()[$i]->reson == 6){echo 'selected';} ?> value='6'> Other </option>
                                                    </select>
                                                </td>
                                                <td><?=$offlineUnits->results()[$i]->offlineDateTime?></td>
                                            </tr>

                                        <?php
                                    }
                                }
                            ?>
                        </tbody>
                    </table>                  
                </div>                
            </div>
        </div>

    </div>
</div>


<?php include_once 'includes/footer.php'; ?>

<script>

$(document).ready(function() {
 
   
    
    $('.editable').change(function(){
        var id = $(this).attr('id');
        var text = $(this).val();
        var field = 'reson';
        $.ajax({
			type: 'POST',			
			url : "live_edit.php",				
			data: {id:id, field:field, value:text, action:'edit'},			
			success: function (response) {
										
			}
		});

    });



    
});




</script>
