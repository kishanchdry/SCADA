<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Pipe Size </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-8">
                        <h6 class="m-0 font-weight-bold text-primary">Pipe Size Report </h6>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>#</th>
                                <th>Unit</th>
                                <th>Panel No.</th>
                                <th>Location</th>
                                <th>Zone</th>
                                <th>Address</th>
                                <th>Pipe Size</th>              
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                           
                               
                                        $c_rtc_dd = date('d');
                                        $c_rtc_mm = date('i');
                                        $c_rtc_hh = date('H');

                                        try {
                                               $query="SELECT 
                                                            u.user_name, 
                                                            u.user_panel_no, 
                                                            u.user_zone,
                                                            u.user_location, 
                                                            u.user_address, 
                                                            ls.pipe_size
                                                        FROM 
                                                            sd_user u  
                                                        INNER JOIN 
                                                            sd_live_panel_status ls 
                                                        ON 
                                                            u.user_id=ls.user_id                                                     
                                                        ORDER BY 
                                                            u.user_name
                                                        ASC";

                                            $unit = $data->selectJoinAll($query);
                                            if (!empty($unit)) {
                                                $c=$unit->count();
                                                    
                                                for ($i=0;$i<$c;$i++) {
                                                    ?>
                                                        <tr>
                                                            <th><?php echo $i+1; ?></th>
                                                            <td><?php echo $unit->results()[$i]->user_name; ?></td>
                                                            <td><?php echo $unit->results()[$i]->user_panel_no; ?></td>
                                                            <td><?php echo 'Phase '.$unit->results()[$i]->user_location; ?></td>
                                                            <td><?php echo 'Zone '.$unit->results()[$i]->user_zone; ?></td>
                                                            <td><?php echo $unit->results()[$i]->user_address; ?></td>
                                                            <td><?php echo $unit->results()[$i]->pipe_size;?></td>
                                                        </tr>
                                                        <?php
                                                }
                                            }
                                        } catch (Exception $e) {
                                            die($e->getMessage());
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

