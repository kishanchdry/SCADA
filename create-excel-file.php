<?php
include "core/init.php";
$db = new User();

$myfile = fopen("files/Offline-Units-balotra.xls", "w") or die("Unable to open file!");

//$queryRes = $db->selectAll('offlineunits');
$queryRes = $db->selectJoinAll('SELECT u.user_name, z.zone_name, p.phase_name, u.user_address, c.date_time FROM comm_status c LEFT JOIN sd_user u ON c.user_id = u.user_id LEFT JOIN sd_industrial_phase p ON c.phase_id = p.phase_id LEFT JOIN sd_industrial_zone z ON c.zone_id = z.zone_id WHERE c.status != 1');

$header[]= "S.No.";
$header[]= "Name";
$header[]= "Area";
$header[]= "Zone";
$header[]= "Address";
$header[]= "Reason";
$header[]= "Offline Date-Time";


$data = '';

$data .= join("\t", $header)."\n";

for( $i=0;$i<$queryRes->count();$i++){
$row1 = array();

switch ($queryRes->results()[$i]->reson) {
  case 0:
    $reson = 'N/A';
    break;
  case 1:
    $reson = 'Controller';
    break;
  case 2:
    $reson = 'GPS';
    break;
  case 3:
    $reson = 'SMPS';
    break;
  case 4:
    $reson = 'Contactor';
    break;
  case 5:
    $reson = 'Closed';
    break;
  case 6:
    $reson = 'Other';
    break;
}


$row1[] = $i+1;
$row1[] = $queryRes->results()[$i]->user_name;
$row1[] = $queryRes->results()[$i]->phase_name;
$row1[] = 'Zone '.$queryRes->results()[$i]->zone_name;
$row1[] = trim($queryRes->results()[$i]->address);
$row1[] = $reson;
$row1[] = $queryRes->results()[$i]->date_time;
$data .= join("\t", $row1)."\n";
//$data= $first_name."\t";
//$data .= $row['originator']."\t";
}

fwrite($myfile, $data);
fclose($myfile);

$message =  
"<table>
  <tr>
    <th>
      CETP-Balotra (Balotra Water Pollution Control, Treatment & Research Foundatin) Unit Communication Details.
      <br>
    </th>  
  </tr>
</table>
<table>  
  <tr>
    <th style='border:1px solid #333;padding:3px  8px;'>Total Units</th><td style='border:1px solid #333;padding:3px  8px;'>347</td>
  </tr>
  <tr>
    <th style='border:1px solid #333;padding:3px  8px;'>Online Units</th><td style='border:1px solid #333;padding:3px  8px;'>".(347-$queryRes->count())."</td>
  </tr>
  <tr>
    <th style='border:1px solid #333;padding:3px  8px;'>Offline Units</th><td style='border:1px solid #333;padding:3px  8px;'>".$queryRes->count()."</td>
  </tr>
</table>";

echo sendMail($message);

?>