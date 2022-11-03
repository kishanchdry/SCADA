<?php 
include_once 'includes/header.php';
$data = new User();
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
    
            try {
                $data->adding('sd_industrial_phase_zone', array(
                    'phase_id' => Input::get('phase'),
                    'zone_id' => Input::get('zone')
                ));

                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Success :-
                                <div class="text-white-80 small">New Zone Added !</div>
                            </div>
                        </div>';

                // Redirect::to('add-organization.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
      
    }
}


?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Add Zones </a></p>
</div>



<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Add Zones </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Location :
                        </div>
                        <div class="col-sm-8 mb-3 mb-sm-0">
                            <?php
                                $data = $data->selectAll('sd_industrial_phase');
                            ?>
                            <select class="form-control" name="phase" onchange='get_not_zone(this.value)' >
                                <?php
                                    $c = $data->count();
                                    for($i=0;$i<$c;$i++){ 
                                ?>
                                    <option value="<?php echo $data->results()[$i]->phase_id; ?>"><?php echo $data->results()[$i]->phase_name; ?></option>
                                <?php } ?>    
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            Zone :
                        </div>
                        <div class="col-sm-8 mb-3 mb-sm-0">
                          
                            <select class="form-control" name="zone" id='not-zones'>
                                 
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                            <input type="submit" class="btn btn-primary" name="register" value="Add-Zone" />
                            <br>
                            <br>
                        </div>
                        <br>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <div class="col-lg-8">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">All Zones </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Running %</th>
                                <th>Active(Unlock)</th>
                                <th>PLC (Reset)</th>
                                <th>Update</th>
                                
                            </tr>
                        </thead>
                        <tbody id='zones_data'>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<?php include_once 'includes/footer.php'; ?>
<script src="js/custom.js"></script>
<script>
    get_not_zone(1);
</script>