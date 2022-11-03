<?php 
include_once 'includes/header.php';
$data = new User();


?>

<style>
        .MultiCheckBox {
            border:1px solid #e2e2e2;
            padding: 5px;
            border-radius:4px;
            cursor:pointer;
        }

        .MultiCheckBox .k-icon{ 
            font-size: 15px;
            float: right;
            font-weight: bolder;
            margin-top: -7px;
            height: 10px;
            width: 14px;
            color:#787878;
        } 

        .MultiCheckBoxDetail {
            display:none;
            position:absolute;
            border:1px solid #e2e2e2;
            overflow-y:hidden;
        }

        .MultiCheckBoxDetailBody {
            overflow-y:scroll;
            background: #FFF;
            z-index: 9;
            position: relative;
            padding:5px;
        }

            .MultiCheckBoxDetail .cont  {
                clear:both;
                overflow: hidden;
                padding: 2px;
            }

            .MultiCheckBoxDetail .cont:hover  {
                background-color:#cfcfcf;
            }

            .MultiCheckBoxDetailBody > div > div {
                float:left;
                margin-right: 5px;
                font-size: 11px;
            }

        .MultiCheckBoxDetail>div>div:nth-child(1) {
        
        }

        .MultiCheckBoxDetailHeader {
            overflow:hidden;
            position:relative;
            height: 28px;
            background-color:#3d3d3d;
        }

            .MultiCheckBoxDetailHeader>input {
                position: absolute;
                top: 4px;
                left: 3px;
            }

            .MultiCheckBoxDetailHeader>div {
                position: absolute;
                top: 5px;
                left: 24px;
                color:#fff;
            }
    </style>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Monthly Flow </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-12 mb-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Filter Options </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group row">
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <input type="month" name="date" required class="form-control  form-control-sm" id="dateInput">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <?php
                                        $phase = $data->selectAll('sd_industrial_phase');
                                        $c=$phase->count();
                                    ?>
                                    <select class="form-control form-control-sm"  onchange="get_zone(this.value)" id="phase"  name="phase">
                                        <option value="">----Select Location----</option>
                                        <?php 
                                        for($i=0;$i<$c;$i++){
                                        ?>
                                        <option value="<?php echo $phase->results()[$i]->phase_id; ?>"><?php echo $phase->results()[$i]->phase_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                
                                    <select class="form-control form-control-sm" name="zone"  onchange="get_users(this.value, $('#phase').val())"  id="zones">
                                    <option value="">----Select Zone----</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <select class="form-control form-control-sm" name='units' id="test">
                                    <option value="">----Select User----</option>
                                    </select>
                                    <input type="hidden" id="users" name='users'  />   
                                </div>
                            </div>
                        </div>                       
                    </div> 
                      
            </div>
            <div class="card-footer text-right">
                <input type="submit" name='submit' class="btn btn-success" value="Submit" />                                 
            </form>  
            </div>
        </div>

    </div>
<?php 


if(isset($_POST['submit'])){

        $date = $_POST['date'];
        $location = $_POST['phase'];
        $zone = $_POST['zone'];
        $units = $_POST['users'];
        $ucon = 'IN';
        $lcon = 'IN';
        $zcon = 'IN';
        $start_date= '01 '.date('M Y', strtotime($date));
      
        $last_date = date('d M Y', strtotime( '-1 days' ));

        if(date('m', strtotime($date)) != date('m')){
           
            $last_date = date("t M Y", strtotime($date));
        }

        if(empty($units)){
            $units = 0;
            $ucon = 'NOT IN';
        }

        if(empty($location)){
            $location = 0;
            $lcon = 'NOT IN';
        }

        if(empty($zone)){
            $zone = 0;
            $zcon = 'NOT IN';
        }

        $month = date('m', strtotime($date));
        $year =  date('Y', strtotime($date));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $query = 'SELECT 
                    u.user_id as SNO, 
                    u.user_name AS Unit, 
                    p.phase_name AS Location, 
                    z.zone_name AS Zone, 
                    u.user_panel_no AS PanelNo, 
                    u.user_limit AS Totallimit, 
                    u.user_address AS Address, ';

        for($i=1;$i<=$days;$i++){

            $query.='ROUND(SUM(case when date(t.tdate) = "'.$year.'-'.$month.'-'.$i.'"  then t.totalizer_diff else 0 end), 2) as "'.date('Y-m', strtotime($date)).'-'.$i.'"';
        
            if($i < $days){
                    $query.= ', ';
            }
        }            
                    
        $query.='FROM
                    sd_user u    
                    JOIN sd_industrial_phase p ON p.phase_id = u.user_location
                    JOIN sd_industrial_zone z ON z.zone_id = u.user_zone
                    JOIN tester t ON t.id = u.user_id
                WHERE  u.user_location '.$lcon.' ('.$location.')
                    AND u.user_zone '.$zcon.' ('.$zone.') 
                    AND u.user_id  '.$ucon.'  ('.$units.')
                GROUP BY t.id, u.user_name, p.phase_name, u.user_panel_no, z.zone_name, u.user_limit, u.user_address 
                ORDER BY p.phase_name ASC, z.zone_name ASC, u.user_name ASC';
       
        $flow = $data->selectJoinAll($query);

        
        ?>
        
        <script>

                var start_date = '<?=$start_date?>';
                var last_date = '<?=$last_date?>';

        </script>

    <div class="col-lg-12 mb-4">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-6">
                        <h6 class="m-0 font-weight-bold text-primary">Monthly Flow Report </h6>
                    </div>
                    <div class="col-xl-6 text-right">
                        <a  id="ecportExcel" title="myTable" alt="monthely_flow" class="btn btn-success btn-circle btn-sm text-light">
                            <i class="fas fa-file-excel  text-light"></i>
                        </a>

                        <button title="Export to PDF" onclick="getPDF('myTable')" class="btn btn-success btn-circle btn-sm">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
               
                <div class="table-responsive">
                    <table class="table table-bordered table-sm myTable" id="myTable" width="100%" cellspacing="0">
                        <?php
                            if (!empty($flow)) {
                                $c = $flow->count();
                           
                                for ($row = 0; $row < $c; $row++) {                                    
                                    // echo "<p><b>Row number $row</b></p>";
                                    if ($row === 0) {
                                        echo "<thead><tr>";
                    
                                        // echo "Column Count" .count($flow->results()[$row]);
                                    
                                        foreach ($flow->results()[$row] as $key => $value) {
                                            echo "<th class='hbg-primary'><strong>".$key."</strong></th>";
                                        }
                                        echo "</tr></thead>";
                                    }
                    
                                    echo "<tr>";
                    
                                    // echo "Column Count" .count($flow->results()[$row]);
                                
                                    foreach ($flow->results()[$row] as $key => $value) {
                                      if($key == 'SNO'){
                                        echo "<td>". ($row+1) ."</td>";
                                      }else{

                                        if($value === '1.00'){
                                            echo "<td>-</td>";
                                        }else{
                                            echo "<td>".$value."</td>";
                                        }
                                       
                                      }
                                        
                                    }
                                    echo "</tr>";
                                }
                        
                            }else{
                                echo "No data Found !";
                            }
                    
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <?php
}


?>
    


</div>


<?php include_once 'includes/footer.php'; ?>

<script>
    monthelySum();
</script>