<?php 
include_once 'includes/header.php';
$data = new User();
if(Input::exists()){
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation =$validate->check($_POST, array(
            'location' => array(
                'required' => true
            ),
            'address' => array(
                'required' => true
            ),
            'operator_name' => array(
                'required' => true,
                'min' => 4,
                'max' => 70,
            ),
            'operator_mobile' => array(
                'required' => true
            )
        ));

        if ($validation->passed()) {
            
            try{
                $data->adding('sd_industrial_phase', array(
                    'phase_name' => Input::get('location'),
                    'phase_address' => Input::get('address'),
                    'op_name' => Input::get('operator_name'),
                    'op_mobile' => Input::get('operator_mobile'),
                ));
            }catch(Exception $e){
                die($e->getMessage());
            }

            // to show flash notification to another page
           // Redirect::to('deshboard.php');
          // Session::flash('Registered', 'New user registered !');
           
          
                echo    '<div id="notification" class="card bg-success text-white shadow">
                            <div class="card-body">
                            Registered :-
                                <div class="text-white-80 small">New Location Added !</div>
                            </div>
                        </div>';
           
                       // Redirect::to('add-organization.php');
            
        } else {
            
            echo    '<div id="notification" class="card bg-danger text-white shadow">
                        <div class="card-body">
                            Errors :-';
                  
            foreach($validation->errors() as $errors){
                        echo ' <div class="text-white-80 small">'.$errors.'</div>';
            }
           
            echo        '</div>
                    </div>';
           
        }
    }

}


?>


<div class="mb-4 p-1 text-info">
        <p><a href="settings.php">Settings </a> >> <a href="#">Add Location </a></p>
</div>


<div class="row" id='top'>

    <div class="col-lg-5">
        <div class="card border-left-info shadow h-100">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-12">
                        <h6 class="m-0 font-weight-bold text-primary">Add new Location </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="user" method="post" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" name="location" class="form-control " id="Name"
                                value="<?php echo escape(Input::get("location")); ?>" placeholder="Location">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" name="address" class="form-control " 
                                value="<?php echo escape(Input::get("address")); ?>" placeholder="Address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" name="operator_name" class="form-control "
                                value="<?php echo escape(Input::get("operator_name")); ?>" placeholder="Operator Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" name="operator_mobile" class="form-control " 
                                value="<?php echo escape(Input::get("operator_mobile")); ?>"
                                placeholder="Operator`s Mobile Number">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                            <input type="submit" class="btn btn-primary" name="register" value="Add-Location" />
                            <br>
                            <br>
                        </div>
                        <br>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <div class="col-lg-7">
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
                                <th>OP Name</th>
                                <th>OP Mobile</th>
                                <th>Limit</th>
                                <th>Active</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   
                                    $u_data = $data->selectAll('sd_industrial_phase');
                                    $c=$u_data->count();
                                    for ($i=0; $i<$c; $i++) {
                            ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $u_data->results()[$i]->phase_name; ?></td>
                                <td><?php echo $u_data->results()[$i]->phase_address; ?></td>
                                <td><?php echo $u_data->results()[$i]->op_name; ?></td>
                                <td><?php echo $u_data->results()[$i]->op_mobile; ?></td>
                                <td>
                                    
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="number" id='phase_limit<?php echo $u_data->results()[$i]->phase_id; ?>' min="0" max="100" name='phase_limit' 
                                               value="<?php echo $u_data->results()[$i]->phase_limit; ?>" />
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>

                                        </div>

                                </td>
                                <td class="text-center">
                                   
                                    <input type="checkbox" id="phase_state<?php echo $u_data->results()[$i]->phase_id; ?>" name="phase_state"
                                        <?php if($u_data->results()[$i]->phase_state==1){ echo 'checked';} ?>
                                    /></td>
                                <td class="text-center">
                                    <input type="button" onclick='updatePhase(<?php echo $u_data->results()[$i]->phase_id; ?>)' name='update' value="Update" class="btn btn-info btn-sm">
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

