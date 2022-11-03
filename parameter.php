<?php 
include_once 'includes/header.php';
$data = new User();
if(Input::exists()){
    if (Token::check(Input::get('token'))) {
                  $alarm_state = Input::get('alarm-state');
            try{

                $data->SetQuery('ALTER TABLE alarm ALTER COLUMN a_alarm SET DEFAULT'. $alarm_state);
                $data->SetQuery('UPDATE alarm SET a_alarm ='.$alarm_state);

                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Registered :-
                                <div class="text-white-80 small">New Parameter Set !</div>
                            </div>
                        </div>';
           
                        Redirect::to('parameter.php');
            

            }catch(Exception $e){
                die($e->getMessage());
            }
          
                
    
    }

}


?>



<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Parameters </a></p>
</div>

<div class="row" id='top'>

    <div class="col-lg-5">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Parameters </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-2 mb-3 mb-sm-0">
                            Door :
                        </div>
                        <div class="col-sm-10 mb-3 mb-sm-0">
                            <?php
                                $data = $data->selectAll('alarm');
                                $data= $data->results()[0];
                            ?>
                            <select class="form-control" name="alarm-state">
                                <option
                                    <?php if( $data->a_acknoledge==0){echo "selected";} ?>
                                    value="0">---Door ON---</option>
                                <option
                                    <?php if($data->a_acknoledge==1){echo "selected";} ?>
                                    value="1">---Door OFF---</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                            <input type="submit" class="btn btn-primary" name="register" value="Add" />
                            <br>
                            <br>
                        </div>
                        <br>
                    </div>
                </form>

            </div>
        </div>

    </div>
    
</div>


<?php include_once 'includes/footer.php'; ?>

