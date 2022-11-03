<?php 
include_once 'includes/header.php';
$data = new User();

?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Update </a></p>
</div>

<div class="row" id='top'>

    <div class="col-lg-6">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">All Units OLd Limits </h6>
                    </div>
                </div>
            </div>
            <form method='post'>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Limit</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <?php   
                                    $u_dat = $data->selectJoinAll('SELECT * FROM sd_user ORDER BY user_name ASC');
                                    $c=$u_dat->count();

                                  $address = [];
                                    for ($i=0; $i<$c; $i++) {
                                        if ($u_dat->results()[$i]->user_address != 'Admin') {

                                           $address[]=trim($u_dat->results()[$i]->user_address);

                                            ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $u_dat->results()[$i]->user_name; ?></td>
                                <td><?php echo $u_dat->results()[$i]->user_address; ?></td>
                                <td>
                                    <input type='hidden' value="<?php echo $u_dat->results()[$i]->user_id; ?>" name='units[]' />
                                    <input type='text' name="limits[]" value="<?php echo $u_dat->results()[$i]->user_limit; ?>" class='form-control' name='limit[]' />
                                </td>
                            </tr>
                            <?php
                                        }
                                    }
                                  //  print_r($address);

                            ?>
                             
                        </tbody>
                    </table>
                </div>
                <input type='submit' class="btn btn-success" value="Update" name='update' />
                            </form>
            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">All Units New Limits </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dataTable1" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Limit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   
                                    $u_data = $data->selectJoinAll('SELECT * FROM new_limit ORDER BY user_name ASC');
                                    $c=$u_data->count();
                                    $new_limit = [];
                                    for ($i=0; $i<$c; $i++) {
                                        $key=trim($u_data->results()[$i]->user_address);
                                        $key = $new_str = str_replace(' ', '', $key);
                                        $new_limit[$key] = trim($u_data->results()[$i]->user_limit);
                            ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $u_data->results()[$i]->user_name; ?></td>
                                <td><?php echo $u_data->results()[$i]->user_address; ?></td>
                                <td>
                                    <input type='hidden' value="" name='units[]' />
                                    <input type='text' value="<?php echo $u_data->results()[$i]->user_limit; ?>" class='form-control' name='limit[]' />
                                </td>
                            </tr>
                            <?php
                                    }

                                    //print_r($new_limit);

                                    // $c=count($address);
                                    // for($i=0;$i<$c;$i++){  
                                    //    //  $address[$i].' - '.$new_limit[$address[$i]].' - Updated'; 
                                    //         $data = new User();
                                    //         $limit = $new_limit[$address[$i]];
                                    //       //echo '-';
                                    //         $addrs = $address[$i];
                                    //       //echo '- ,';
                                    //         if(!empty( $limit)){
                                    //              $query = 'UPDATE sd_user SET user_limit ='.$limit.' WHERE user_address = "'.$addrs.'"';
                                    //             $result = $data->selectJoinAll($query);
                                    //         }
                                    // }
                                if(isset($_POST['update'])){
                                    $units = $_POST['units'];
                                    $limits = $_POST['limits'];

                                    $c=count($units);
                                    for($i=0;$i<$c;$i++){  
                                      
                                        $query = 'UPDATE sd_user SET user_limit ='.$limits[$i].' WHERE user_id = "'.$units[$i].'"';
                                        $result = $data->selectJoinAll($query);
                                        $data->adding('updates', array(
                                            'user_id' => $units[$i],
                                            'flow' => '-',
                                            'kldlimit' => $limits[$i],
                                            'times' => '-',
                                            'user' => $user
                                        ));
                                         
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





