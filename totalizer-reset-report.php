<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="reports.php">Reports </a> >> <a href="#">Reset Totalizer </a></p>
</div>


<div class="row" id='top'>
    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-3">
                        <h6 class="m-0 font-weight-bold text-primary">Totalizer Reset Report </h6>                        
                    </div>
                    <div class="col-xl-3">
                        <form method='post'><input type='submit' class="btn btn-info btn-sm" value='Online' name='online' /></form>                     
                    </div>
                    <div class="col-xl-3">
                    <form method='post'><input type='submit' class="btn btn-info  btn-sm" value='Offline' name='offline' /></form>                        
                    </div>
                    <div class="col-xl-3 text-right">
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
                                <th>Date Time</th>                   
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                        if(isset($_POST['offline'])){
                                            $query="SELECT 
                                                        u.user_name, 
                                                        u.user_panel_no, 
                                                        u.user_zone,
                                                        u.user_location, 
                                                        u.user_address,
                                                        ls.date_time
                                                    FROM 
                                                        sd_user u  
                                                    LEFT JOIN 
                                                        sd_live_panel_status ls 
                                                    ON 
                                                        u.user_id=ls.user_id 
                                                    WHERE 
                                                        Date(ls.date_time) != date(now())    
                                                    AND 
                                                        ls.totalizer > 1
                                                    ORDER BY 
                                                        u.user_name
                                                    ASC";
                                        }else if(isset($_POST['online'])){
                                            $query="SELECT 
                                                        u.user_name, 
                                                        u.user_panel_no, 
                                                        u.user_zone,
                                                        u.user_location, 
                                                        u.user_address,
                                                        ls.date_time
                                                    FROM 
                                                        sd_user u  
                                                    LEFT JOIN 
                                                        sd_live_panel_status ls 
                                                    ON 
                                                        u.user_id=ls.user_id 
                                                    WHERE 
                                                        Date(ls.date_time) = date(now())    
                                                    AND 
                                                        ls.totalizer > 1
                                                    ORDER BY 
                                                        u.user_name
                                                    ASC";
                                        }else{
                                            
                                                $query="SELECT 
                                                            u.user_name, 
                                                            u.user_panel_no, 
                                                            u.user_zone,
                                                            u.user_location, 
                                                            u.user_address,
                                                            ls.date_time
                                                        FROM 
                                                            sd_user u  
                                                        LEFT JOIN 
                                                            sd_live_panel_status ls 
                                                        ON 
                                                            u.user_id=ls.user_id 
                                                        WHERE                                                            
                                                            ls.totalizer > 1
                                                        ORDER BY 
                                                            u.user_name
                                                        ASC";
                                           
                                        }
                                   

                                        


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
                                                        <td><?php echo $unit->results()[$i]->date_time; ?></td>
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

