<?php
// include "core/init.php";
// $db = new User();

// $myfile = fopen("files/offline-Units.xls", "w") or die("Unable to open file!");

// $queryRes = $db->selectAll('offlineunits');

// $header[]= "S.No.";
// $header[]= "Name";
// $header[]= "Area";
// $header[]= "Zone";
// $header[]= "Address";
// $header[]= "Reason";
// $header[]= "Offline Date-Time";


// $data = '';

// $data .= join("\t", $header)."\n";

// for( $i=0;$i<$queryRes->count();$i++){
// $row1 = array();

// switch ($queryRes->results()[$i]->reson) {
//   case 0:
//     $reson = 'N/A';
//     break;
//   case 1:
//     $reson = 'Controller';
//     break;
//   case 2:
//     $reson = 'GPS';
//     break;
//   case 3:
//     $reson = 'SMPS';
//     break;
//   case 4:
//     $reson = 'Contactor';
//     break;
//   case 5:
//     $reson = 'Closed';
//     break;
//   case 6:
//     $reson = 'Other';
//     break;
// }


// $row1[] = $i+1;
// $row1[] = $queryRes->results()[$i]->name;
// $row1[] = $queryRes->results()[$i]->location;
// $row1[] = 'Zone '.$queryRes->results()[$i]->zone;
// $row1[] = trim($queryRes->results()[$i]->address);
// $row1[] = $reson;
// $row1[] = $queryRes->results()[$i]->offlineDateTime;
// $data .= join("\t", $row1)."\n";
// //$data= $first_name."\t";
// //$data .= $row['originator']."\t";
// }

// fwrite($myfile, $data);
// fclose($myfile);

?>
<input type='text' name='ids' id='ids' />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
setInterval(() => {
  var ids = $('#ids').val();
  $.ajax({
    url: "offline-online-units.php",
    type: "POST",
    data:{ids:ids},
    success: function(results) { }
});
}, 240000);


</script>