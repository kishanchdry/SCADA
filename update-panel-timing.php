<?php 
include_once 'includes/header.php';
$data = new User();

$lock_mode=$data->selectJoinAll('SELECT * FROM shedule_panel_lock_mode');
//print_r($lock_mode);
$times = $lock_mode->results()[0]->times;
$dates = $lock_mode->results()[0]->dates;
$status = $lock_mode->results()[0]->status;

$calculations = new Calculations();
?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Update </a></p>
</div>


<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">All Units OLd Limits </h6>
                    </div>
                </div>
            </div>           
            <div class="card-body">  
                <form method='post' >
                    <div class="row" >
                        <div class="col-lg-5">   
                            <input type='hidden' class="form-control" value="Shedular@1234" name='password' />
                            <input type='password' class="form-control" value="" placeholder='Enter the password... ' name='userpassword' />                
                        </div>
                        <div class="col-lg-5">   
                           <select class='form-control' name='mode'>
                                <option value='0'> Deactivate Both </option>
                                <option value='3'> Activate Update Shedule </option>
                                <option value='1'> Times </option>
                                <option value='2'> Dates </option>
                           </select>
                        </div>
                        <div class="col-lg-2">   
                           
                                <input type='submit' class="btn btn-success" value="Submit" name='submit' />
                            
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</div> 
<?php

if(isset($_POST['submit'])){
    $password = $_POST['password'];
    $userpassword = $_POST['userpassword'];
    $mode = $_POST['mode'];
    $activate = '';
    
    switch($mode){
        case 0:
            $_status=0;
            $_times= 0;
            $_dates= 0;
            break;
        case 1:
            $_status=1;
            $_times= 1;
            $_dates= 0;
            break;
        case 2:
            $_status=1;
            $_times= 0;
            $_dates= 1;
            break;
        case 3:
            $_status=1;
            $_times= 0;
            $_dates= 0;
            break;    
    }


    if( $password === $userpassword){
        $data->selectJoinAll('UPDATE shedule_panel_lock_mode SET status = '.$_status.', times='.$_times.', dates='.$_dates.' WHERE id = 1');       
        echo "<script>window.location.href='update-panel-timing.php';</script>";
    }
}




if($status == 1){ 



    ?>
<div class="row" id='top'>
    <div class="col-lg-12">
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
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-info text-light">
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Panel Status Is</th>
                                <th>Panel Status to Be (Time)</th>
                                <th>Times</th>                               
                                <th>Dates</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <?php   
                                    $u_dat = $data->selectJoinAll('SELECT s.*, l.panel_lock FROM sd_user s LEFT JOIN sd_live_panel_status l ON l.user_id = s.user_id');
                                    $c=$u_dat->count();

                                    for ($i=0; $i<$c; $i++) {
                                       
                                            ?>
                                            <tr class="">
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $u_dat->results()[$i]->user_name; ?></td>
                                                <td><?php echo $u_dat->results()[$i]->user_address; ?></td>
                                                <td><?php echo ($u_dat->results()[$i]->panel_lock == 1) ? 'Lock' : 'Un-Lock'; ?></td>
                                                <td><?php echo ($calculations->autoTimesShedulePanelLock($u_dat->results()[$i]->panel_unlock_timing) == 1) ? 'Lock' : 'Un-Lock'; ?></td>
                                                <td>
                                                    <input type='hidden' value="<?php echo $u_dat->results()[$i]->user_id; ?>" name='units[]' />
                                                    <input type='text' name="times[]" value="<?php echo $u_dat->results()[$i]->panel_unlock_timing; ?>" class='form-control' name='limit[]' />
                                                </td>
                                                <td>
                                                   
                                                    <input type='text' name="dates[]" value="<?php echo $u_dat->results()[$i]->panel_unlock_dates; ?>" class='form-control' name='limit[]' />
                                                </td>
                                            </tr>
                                            <?php
                                                      
                                        }
                                        

                            ?>
                             
                        </tbody>
                    </table>
                </div>
                <input type='submit' class="btn btn-success" value="Update" name='update' />
                            </form>
            </div>
        </div>

    </div>
    
                            <?php
                                    

                                  
                                if(isset($_POST['update'])){
                                    $units = $_POST['units'];
                                    $dates = $_POST['dates'];
                                    $times = $_POST['times'];

                                    $c=count($units);
                                    for($i=0;$i<$c;$i++){  
                                      
                                        $query = 'UPDATE sd_user SET panel_unlock_timing ="'.$times[$i].'", panel_unlock_dates ="'.$dates[$i].'"  WHERE user_id = '.$units[$i];
                                        $result = $data->selectJoinAll($query);

                                        $data->adding('updates', array(
                                            'user_id' => $units[$i],
                                            'flow' => '-',
                                            'kldlimit' => '-',
                                            'times' => $times[$i]." | ".$dates[$i],
                                            'user' => $user
                                        ));
                                         
                                    }
                                    echo "<script>window.location.href='update-panel-timing.php';</script>";
                                }
                            ?>
     </div>
<?php  } ?>

<?php include_once 'includes/footer.php'; ?>





