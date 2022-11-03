<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Update </a></p>
</div>

<div class="row" id='top'>

    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">All Location </h6>
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
                                <th>Address</th>
                                <th>Panel No.</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   
                                    $u_data = $data->selectAll('sd_user');
                                    $c=$u_data->count();
                                    for ($i=0; $i<$c; $i++) {
                            ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $u_data->results()[$i]->user_name; ?></td>
                                <td><?php echo $u_data->results()[$i]->user_address; ?></td>
                                <td><?php echo $u_data->results()[$i]->user_panel_no; ?></td>
                                <td class="text-center">
                                    <!--<a href="update-unit.php?u=<?php //echo $u_data->results()[$i]->user_id; ?>"><i class="fa fa-edit"></i></a>--->
                                </td>
                            </tr>
                            <?php
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





