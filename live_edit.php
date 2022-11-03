<?php
include_once("core/init.php");
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{

	$db = new User();

	$id = $_POST['id'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	$action = $_POST['action'];

	if($action == 'edit'){
	
		$result =$db->selectJoinAll('UPDATE offlineunits SET '.$field.' = "'.$value.'" WHERE   id = '.$id);

		if($result){
			echo 'OK';
		}
	}

}

