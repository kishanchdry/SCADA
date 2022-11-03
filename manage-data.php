<?php 
include_once 'includes/header.php';
$data = new User();

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
            $id = Input::get('id');
            try {
                $data->gsdg('tester', $id, 'id', array(
                    'totalizer_diff' => Input::get('totalizer')
                ));

                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Success :-
                                <div class="text-white-80 small">Data Updated !</div>
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
        <p><a href="settings.php">Settings </a> >> <a href="#">Manage Data </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Micro Scada Details </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Location :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <?php
                                $phase = $data->selectAll('sd_industrial_phase');
                                $c=$phase->count();
                            ?>
                            <select class="form-control form-control-sm" onchange="get_zone(this.value)" id="phase"  name="phase">
                                <option value="">----Select Location----</option>
                                <?php 
                                   for($i=0;$i<$c;$i++){
                                ?>
                                <option value="<?php echo $phase->results()[$i]->phase_id; ?>"><?php echo $phase->results()[$i]->phase_name; ?></option>
                                   <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Zone :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                           
                            <select class="form-control form-control-sm" name="zone"  onchange="get_users(this.value, $('#phase').val())"  id="zones">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Unit :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                          
                            <select class="form-control form-control-sm" id="users"   name="user">
                            <option value="">----Select User----</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            Date :
                        </div>
                        <div class="col-sm-7 mb-3 mb-sm-0">
                            <input type="date" name="date" class="form-control " id="Date"
                                value="<?php echo escape(Input::get("date")); ?>">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            
                            <input type="submit" class="btn btn-primary" name="show" value="Show" />
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
                    <div class="col-xl-8">
                        <h6 class="m-0 font-weight-bold text-primary">Micro Scada Data </h6>
                    </div>
                    <div class="col-xl-4 text-right">
                        <button onclick="export_excel('dataTable')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-excel"></i>
                        </button>

                        <button title="Export to PDF" onclick="getPDF('dataTable')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="checkbox" id="select-all" onclick="selectAll()"> Select All
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Select</th>
                                <th>Date-Time </th>
                                <th>Value</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                if(isset($_POST['show'])) {

                                        $userid =Input::get('user');
                                        $date=Input::get('date');
                                        $dd=date_create_from_format('Y-m-d', $date);    
                                        $day = date_format($dd, 'Y_m_d');
                                        $table= 'day_'.$day;

                                        try{
                                            
                                                $con=new PDO('mysql:host=localhost;dbname=scadalast7daysdata', 'root', 'V*ision*123');
                                                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                                
                                                $query="SELECT * FROM ".$table."  WHERE user_id = ".$userid;
                                                $qr = $con->query($query);
                                                $qr->setFetchMode(PDO::FETCH_ASSOC);
                                                $data = $qr->fetchAll();
                                                $i=1;
                                                foreach($data as $D){
                                                ?>
                                                <tr>
                                                    <td><?php print $i;?></td>
                                                    <td><input type="checkbox" id="selected" class="selected" value="<?php print $D['date_time']; ?>" /></td>
                                                    <td><?php print $D['date_time']; ?></td>
                                                    <td><?php print $D['totalizer']; ?></td>
                                                    <td><i class="text-danger fa fa-times-circle cursor-pointer" onclick="deleteAll(<?php print $D['user_id']; ?>)"></i></td>
                                                </tr>
                                                <?php
                                                        $i++;
                                                    }
                                            }catch(Exception $e){
                                                die($e->getMessage());
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
$(document).ready(function(){
    $("select[name='dataTable_length']").append('<option value="1000">1000</option><option value="2000">2000</option>');
});
</script>

