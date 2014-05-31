<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	// common.php includen - da kommen die funktionen rein die immer gebraucht werden;
	require_once("../sys/common.php");
	require_once("../sys/objects.php");
	
	//session, ui und db starten
	$session = new Session();
	$ui = new UI();
	$db = new DB();
	
	$saveapps = '';
		
	$db->saveapp($saveapps);

?>