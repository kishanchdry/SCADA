<?php 
include_once 'includes/header.php';

if($user != 2){
    echo "<script>window.location.href='index.php'</script>";
}

$data = new User();


if(isset($_POST['update'])){
            
    $update= $data->updateData('update_comm_status',1, 'id', array(
        'data' => $_POST['data'],
        'units' => $_POST['units'],
        'limits' => $_POST['limits'],
        'totalizerData' => $_POST['totalizerData'],
        'totalizers' => $_POST['totalizers']   
    ));

   
    echo    '<div id="notification" class="card bg-success text-white shadow">
                <div class="card-body">
                Success :-
                    <div class="text-white-80 small">Units Set to Temperary Online !</div>
                </div>
            </div>';
   

}



$temperaryCommStatusData = $data->getData('update_comm_status', array('id', '=', 1));
$temperaryCommStatusData = $temperaryCommStatusData->results()[0];


?>
<div class="row" id='top'>
    <div class="col-lg-8">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-9">
                        <h6 class="m-0 font-weight-bold text-primary">Unit Details </h6>
                    </div>
                    <div class="col-xl-3">
                        <button onclick="setToCommunication()" class="btn btn-info btn-sm">Set To Communication</button>                        
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="dataTable" class="table table-bordered table-sm dataTable no-footer">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Select</th>
                            <th>Name</th>
                            <th>Panel No.</th>
                            <th>Phase</th>
                            <th>Zone</th>
                            <th>Address</th>
                            <th>Limit</th>
                            <th>Last Communication</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php    
                        $units = $data->selectJoinAll('SELECT s.*, s.user_id as uid, c.*, p.*, z.*, l.* FROM sd_user s LEFT JOIN sd_industrial_phase p ON p.phase_id = s.user_location LEFT JOIN sd_industrial_zone z ON z.zone_id = s.user_zone LEFT JOIN comm_status c ON c.user_id = s.user_id LEFT JOIN sd_live_panel_status l ON l.user_id = s.user_id WHERE c.status = 2');
                        if(!empty($units)){
                            $r= $units->count();
                            for($i=0;$i<$r;$i++){
                                ?>
                                <tr>
                                    <td><?=$i+1?></td>
                                    <td><input type="checkbox" class="selected" id="<?=$units->results()[$i]->uid?>" totalizer="<?=$units->results()[$i]->totalizer?>" limit="<?=$units->results()[$i]->user_limit?>"></td>
                                    <td><?=$units->results()[$i]->user_username?></td>
                                    <td><?=$units->results()[$i]->phase_name?></td>
                                    <td><?=$units->results()[$i]->user_panel_no?></td>
                                    <td><?=$units->results()[$i]->zone_name?></td>
                                    <td><?=trim($units->results()[$i]->user_address)?></td>
                                    <td><?=$units->results()[$i]->user_limit?></td>
                                    <td><?=$units->results()[$i]->date_time?></td>
                                </tr>
                                <?php
                            }
                        }else{
                            echo "<tr><td colspan='8'>No data found !</td></tr>";
                        }
                    ?>
                    </tbody>
                </table>               
            </div>
        </div>
    </div> 
    <div class="col-lg-4" >
        <div class="card border-left-info shadow">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-9">
                        <h6 class="m-0 font-weight-bold text-primary">Unit Details </h6>
                    </div>
                    <div class="col-xl-3">
                     
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="#">
                    Units :
                    <input type='text' name="units" value='<?=$temperaryCommStatusData->units?>' class="form-control"/>                 
                    <input type='hidden' name="limits" value='<?=$temperaryCommStatusData->limits?>' class="form-control"/>
                    <input type='hidden' name="totalizers" value='<?=$temperaryCommStatusData->totalizers?>' class="form-control"/>
                    <input type='hidden' name="data" value='<?=$temperaryCommStatusData->data?>' class="form-control"/>
                    <input type='hidden' name="totalizerData" value='<?=$temperaryCommStatusData->totalizerData?>' class="form-control"/><br>
                    <input class="btn btn-success" type="submit" name="update" value="Update" />
                </form>         
            </div>
        </div>
    </div>   
</div>


<?php include_once 'includes/footer.php'; ?>

<script>
        function setToCommunication(){
            var units = new Array();
            var limits = new Array();
            var totalizer = new Array();

            $('.selected').each(function(i, o) {
                if($(this).is(":checked")){
                    units.push($(this).attr('id'));
                    limits.push($(this).attr('limit'));
                    totalizer.push($(this).attr('totalizer'));
                }
            });
            var data =  limits.reduce(function(data, field, index) {
                data[units[index]] = field;
                return data;
            }, {});

            var totalizerData =  totalizer.reduce(function(totalizerData, field, index) {
                totalizerData[units[index]] = field;
                return totalizerData;
            }, {});

            $("input[name='units']").val(units.toString());
            $("input[name='limits']").val(limits.toString());
            $("input[name='totalizers']").val(totalizer.toString());
            $("input[name='data']").val(JSON.stringify(data));
            $("input[name='totalizerData']").val(JSON.stringify(totalizerData));
            
        }  

         setInterval(function() {
            updateCommStatus('_updateCommStatus.php', $("input[name='units']").val(),  $("input[name='limits']").val(), $("input[name='totalizers']").val());
         }, 720000);

        updateCommStatus('_updateDataLive.php', $("input[name='units']").val(),  $("input[name='limits']").val(), $("input[name='totalizers']").val());

        function updateCommStatus(url, units, limits, totalizers){
            $.ajax({
                type: "POST",
                url: url,
                data: { units: units, limits:limits, totalizers:totalizers}, // serializes the form's elements.
                success: function(result) {

                }
            });
        }

</script>