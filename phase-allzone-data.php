<?php 
include_once 'includes/header.php';

$data = new User();

 $p = $_GET['p'];
$location = $data->getData('sd_industrial_phase', array('phase_id', '=', $p));
$location = $location->results()[0]->phase_name;





?>

<div class="mb-4 p-1 text-info">
    <p>
        <a href="dashboard.php">Dashboard </a> >> 
        <a href="phase-all-zones.php?p=<?php echo $p; ?>"> <?php echo $location; ?> </a> >> 
        <a href="#">All Zones </a>
    </p>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-none" >
                <div class="row">
                    <div class="col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" cellspacing="0" >
                                <thead>
                                    <tr class="text-center">
                                        <td class="text-success">* Communicating *</td>
                                        <td class="text-warning">* Today Non Communicating *</td>
                                        <td class="text-danger">* Non Communicating *</td>
                                        <td class="text-secondary">* Alarm *</td>
                                        <td class="text-info">* Runing % *</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12 mb-4">
    <button class="btn btn-info btn-icon-split shadow"  id="select_all" onclick="$('#units').val('all')">
            <span class="icon text-white-50">
                <i class="fas fa-info"></i>
            </span>
            <span class="text">All</span>
        </button>

        <button class="btn btn-success btn-icon-split shadow" id="select_comm" onclick="$('#units').val('online')">
            <span class="icon text-white-50">
                <i class="fas fa-link"></i>
            </span>
            <span class="text">Online</span>
        </button>

        <button class="btn btn-warning btn-icon-split shadow" id="select_noncomm" onclick="$('#units').val('todayoffline')">
            <span class="icon text-white-50">
                <i class="fas fa-unlink"></i>
            </span>
            <span class="text">Today Offline</span>
        </button>
        <button class="btn btn-danger btn-icon-split shadow" id="select_noncomm" onclick="$('#units').val('yesterdayoffline')">
            <span class="icon text-white-50">
                <i class="fas fa-unlink"></i>
            </span>
            <span class="text">Offline</span>
        </button>
        <button class="btn btn-success btn-icon-split shadow" id="select_nonprog" onclick="$('#units').val('currentflow')">
            <span class="icon text-white-50">
                <i class="fas fa-angle-double-right"></i>
            </span>
            <span class="text">Current Flow</span>
        </button>
        <button class="btn btn-dark btn-icon-split shadow" id="select_nonprog" onclick="$('#units').val('weeklyoverflow')">
            <span class="icon text-white-50">
                <i class="fas fa-circle"></i>
            </span>
            <span class="text">Weekly Overflow</span>
        </button>
        <button class="btn btn-danger btn-icon-split shadow" id="select_nonprog" onclick="$('#units').val('todayoverflow')">
            <span class="icon text-white-50">
                <i class="fas fa-circle"></i>
            </span>
            <span class="text">Today Overflow</span>
        </button>


    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-xl-9">
                        <h6 class="m-0 font-weight-bold text-primary">Unit Details Of  <?php echo $location; ?></h6>
                    </div>
                    
                    <div class="col-xl-3">
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="dt"  width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>S.No.</th>
                            <th>Today OverFlow</th>
                            <th>Weekly OverFlow</th>
                            <th>Alarm</th>
                            <th>C.F. Status</th>
                            <th>Unit</th>
                            <th>Zone</th>
                            <th>Comm. Status</th>
                            <th>Last Comm.</th>
                            <th>Address</th>
                            <th>KLD Limit</th>
                            <th>Current Flow</th>
                            <th><?php echo date('d-M-Y');?></th>
                            <th><?php echo date('d-M', strtotime('-1 days'));?></th>
                            <th><?php echo date('d-M', strtotime('-2 days'));?></th>
                            <th><?php echo date('d-M', strtotime('-3 days'));?></th>
                            <th><?php echo date('d-M', strtotime('-4 days'));?></th>
                            <th><?php echo date('d-M', strtotime('-5 days'));?></th>
                            <th><?php echo date('d-M', strtotime('-6 days'));?></th>
                            <th>Overload</th>
                            <th>A/M</th>
                            <th>Panel Lock</th>
                        </tr>    
                    </thead>
                    <tbody id="dt-body" class="small .dt font-weight-bold ">
                        <tr>
                            <td colspan="22" id='test'> 
                                <div class="content">
                                    <div class="loading">
                                        <p>loading</p>
                                        <span></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id='units' value='all' />

<!-- /.container-fluid -->
<?php include 'includes/footer.php'; ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.13.1/underscore-umd-min.js"></script>
<script>
   
    var phase_id = <?php echo $_GET['p']; ?>;

    $.ajax({
        url: "getPhaseAllStaticData.php",
        type: "POST",
        data: { phase: phase_id},
        success: function(staticResult) {            
            if(staticResult != 0){
                staticResult = JSON.parse(staticResult);
                getLiveData(staticResult);
                setInterval(function(){
                    getLiveData(staticResult);
                }, 30000);
            }
        }
    });

    function getLiveData(staticResult){
        $.ajax({
                url: "getPhaseAllLiveData.php",
                type: "POST",
                data: { phase: phase_id},
                success: function(liveResult) {            
                    if(liveResult != 0){
                        liveResult = JSON.parse(liveResult);                        
                    var dataObj =  _.map(staticResult, function(item){
                                        return _.extend(item, _.findWhere(liveResult, { user_id: item.user_id }));
                                    });
                    var tr = '';
                    var filter = $("#units").val();
                    var sno = 1;
                
                    for (var i = 0; i < dataObj.length;i++) {

                        var dailyLimit = dataObj[i]['user_limit'] * dataObj[i]['user_today_limit'] / 100 ;
                        dataObj[i]['daily_limit'] = dailyLimit;

                        if (dataObj[i]['t1'] > dailyLimit || dataObj[i]['t2'] > dailyLimit || dataObj[i]['t3'] > dailyLimit || dataObj[i]['t4'] > dailyLimit || dataObj[i]['t5'] > dailyLimit || dataObj[i]['t6'] > dailyLimit) {
                            
                            dataObj[i]['weekly_overflow'] = "<i class='fa fa-exclamation-circle text-dark'></i>";
                            dataObj[i]['weekly_overflow_status'] = 1;                            
                        }else{
                            dataObj[i]['weekly_overflow'] = '';
                            dataObj[i]['weekly_overflow_status'] = 0; 
                        }

                        if (dataObj[i]['alarm'] == 1 ) {

                            dataObj[i]['alarm_view'] = "<i class='fa fa-bell text-danger'></i>";
                        }else{
                            dataObj[i]['alarm_view'] = ''; 
                        }

                        if (dataObj[i]['today_flow'] > dailyLimit ) {

                            dataObj[i]['today_overflow'] = "<i class='fa fa-exclamation-circle text-danger'></i>";
                            dataObj[i]['today_overflow_status'] = 1; 
                        }else{
                            dataObj[i]['today_overflow'] = '';
                            dataObj[i]['today_overflow_status'] = 0; 
                        }

                        if (dataObj[i]['c_flow'] > 0 ) {

                            dataObj[i]['currentflow'] = "<i class='fa fa-angle-double-right text-success'></i>";
                            dataObj[i]['currentflow_status'] = 1;
                        }else{
                            dataObj[i]['currentflow'] = '';
                            dataObj[i]['currentflow_status'] = 0;
                        }

                        if (dataObj[i]['panel_lock'] == 0 ) {

                            dataObj[i]['panel_lock_view'] = "<i class='fas fa-lock-open text-success'></i>";
                        }else{
                            dataObj[i]['panel_lock_view'] = "<i class='fas fa-lock text-danger'></i>";
                        }

                        if (dataObj[i]['status'] == 0 ) {

                            dataObj[i]['comm_status_view'] = "<i class='fa fa-exclamation-circle text-warning'></i>";
                            dataObj[i]['comm_status'] = 0;
                            dataObj[i]['row_class'] = 'text-warning';

                        }else if(dataObj[i]['status'] == 1){

                            dataObj[i]['comm_status_view'] = "<i class='fa fa-circle text-success'></i>";
                            dataObj[i]['comm_status'] = 1;
                            dataObj[i]['row_class'] = 'text-success';
                        }else{
                            dataObj[i]['comm_status_view'] = "<i class='fa fa-exclamation-circle text-danger'></i>";
                            dataObj[i]['comm_status'] = 2;
                            dataObj[i]['row_class'] = 'text-danger';
                        }

                        if(dataObj[i]['overload'] == 1 ){

                            dataObj[i]['overload_view'] = "<i class='fas fa-times-circle text-danger'></i>"; 
                        }else {
                            dataObj[i]['overload_view'] = "<i class='fa fa-check-circle text-success'></i>";
                        }

                        if(dataObj[i]['auto_manual'] == 1 ){

                            dataObj[i]['auto_manual_view'] = "A"; 
                        }else {
                            dataObj[i]['auto_manual_view'] = "M";
                        } 


                        if(dataObj[i]['t1'] == 1.00){
                            dataObj[i]['d1'] = '-';
                        }else{
                            dataObj[i]['d1'] = dataObj[i]['t1'];
                        }

                        if(dataObj[i]['t2'] == 1.00){
                            dataObj[i]['d2'] = '-';
                        }else{
                            dataObj[i]['d2'] = dataObj[i]['t2'];
                        }

                        if(dataObj[i]['t3'] == 1.00){
                            dataObj[i]['d3'] = '-';
                        }else{
                            dataObj[i]['d3'] = dataObj[i]['t3'];
                        }

                        if(dataObj[i]['t4'] == 1.00){
                            dataObj[i]['d4'] = '-';
                        }else{
                            dataObj[i]['d4'] = dataObj[i]['t4'];
                        }

                        if(dataObj[i]['t5'] == 1.00){
                            dataObj[i]['d5'] = '-';
                        }else{
                            dataObj[i]['d5'] = dataObj[i]['t5'];
                        }

                        if(dataObj[i]['t6'] == 1.00){
                            dataObj[i]['d6'] = '-';
                        }else{
                            dataObj[i]['d6'] = dataObj[i]['t6'];
                        }
                        
                        dataObj.sort((a, b) => a.comm_status - b.comm_status);

                        if(filter == 'all'){

                            tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";
                            sno++;
                        }else if(filter == 'todayoverflow'){ 
                            if(dataObj[i]['today_overflow_status'] == 1){
                                tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";         
                                sno++;
                            }
                        }else if(filter == 'weeklyoverflow'){
                            if(dataObj[i]['weekly_overflow_status'] == 1){
                                tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";
                                sno++;
                            }
                        }else if(filter == 'online'){
                            if(dataObj[i]['comm_status'] == 1){
                                tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";
                                sno++;
                            }
                        }else if(filter == 'todayoffline'){
                            if(dataObj[i]['comm_status'] == 0){
                                tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";
                                sno++;
                            }
                        }else if(filter == 'yesterdayoffline'){
                            if(dataObj[i]['comm_status'] == 2){
                                tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";
                                sno++;
                            }
                        }else if(filter == 'currentflow'){
                            if(dataObj[i]['currentflow_status'] == 1){
                                tr +=   "<tr class='"+dataObj[i]['row_class']+"'><td>"+ sno +"</td><td>"+dataObj[i]['today_overflow']+"</td><td>"+dataObj[i]['weekly_overflow']+"</td><td>"+dataObj[i]['alarm_view']+"</td><td>"+dataObj[i]['currentflow']+"</td><td><a class='"+dataObj[i]['row_class']+"' href='unit-profile.php?u="+dataObj[i]['user_id']+"'>"+dataObj[i]['name']+"</a></td><td>"+dataObj[i]['zone']+"</td><td>"+dataObj[i]['comm_status_view']+"</td><td>"+dataObj[i]['last_comm']+"</td><td>"+dataObj[i]['address']+"</td><td>"+dataObj[i]['user_limit']+"</td><td>"+dataObj[i]['c_flow']+"</td><td>"+dataObj[i]['today_flow']+"</td><td>"+dataObj[i]['d1']+"</td><td>"+dataObj[i]['d2']+"</td><td>"+dataObj[i]['d3']+"</td><td>"+dataObj[i]['d4']+"</td><td>"+dataObj[i]['d5']+"</td><td>"+dataObj[i]['d6']+"</td><td>"+dataObj[i]['overload_view']+"</td><td>"+dataObj[i]['auto_manual_view']+"</td><td>"+dataObj[i]['panel_lock_view']+"</td></tr>";
                                sno++;
                            }
                        }  
                        
                        
                    }

                    $("#dt-body").empty();
                    $("#dt-body").append(tr);
                    }
                }
            });
    }

</script>