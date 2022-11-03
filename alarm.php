<?php 
include_once 'includes/header.php';
$data = new User();

$alarmsData = $data->selectJoinAll('SELECT * FROM alarm_settings ORDER BY id ASC');

?>

<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Alarm Settings </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-12">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-8">
                        <h6 class="m-0 font-weight-bold text-primary">Alarm Settings </h6>
                    </div>
                   
                </div>
            </div>
            <div class="card-body">              
                <div class="row">
                    <div class="col-md-9">
                        <form method="post">
                            <div class="form-group row">
                                <div class="col-sm-3 mb-3 mb-sm-0">
                                    Door Limit Stwich Alarm : 
                                </div>
                                <div class="col-sm-4 mb-3 mb-sm-0">
                                   <input type="text" class="form-control form-control-sm" name="door" value="<?=$alarmsData->results()[0]->state?>"  />  
                                </div>
                                <div class="col-sm-5 mb-3 mb-sm-0">
                                    <input name="doorlimit" class="btn btn-info" type="submit" value="Update" />
                                </div>
                            </div>        
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form method="post">                            
                            <input name="acknowledge" class="btn btn-info" type="submit" value="Acknowledge All Alarms" />                               
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php

if(isset($_POST['doorlimit'])){
    $door = $_POST['door'];
    $update = $data->updateData('alarm_settings', 1, 'id',array('state' => $door));
    if($update){
        echo "<script>alert('Door Alarm Updated')</script>";
    }else{
        echo "<script>alert('Something Went wrong !')</script>";
    }
}

if(isset($_POST['acknowledge'])){
    
    $update = $data->selectJoinAll('UPDATE alarm SET a_acknoledge = 1, a_state = 1, a_date_time_off = now() WHERE date(a_date_time_on) = date(now())');
    if($update){
        echo "<script>alert('All alarm aknowledged !')</script>";
    }else{
        echo "<script>alert('Something Went wrong !')</script>";
    }
}

?>

<?php include_once 'includes/footer.php'; ?>