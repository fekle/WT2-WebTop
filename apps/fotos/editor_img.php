<?php

	//error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	// common.php includen - da kommen die funktionen rein die immer gebraucht werden;
	require_once("../../sys/common.php");
	require_once("../../sys/objects.php");	
	//session, ui und db starten
	$session = new Session();
	$db = new DB();
	
	//schaun ob user eingeloggt ist
	$loggedIn = $session->loggedIn();
	
	$user = $db->userinfo($session->get("user"));
	
	$settings = $db->getSettings();
	
	$uploads = $settings->apps->fotos->uploads;
	
	$id = $_GET["id"];
	
	foreach($uploads as $key=>$upload){
		if($upload->id == $id){
			$tmp = $key;			
			break;
		}
	}
	
	$foto = $uploads = $settings->apps->fotos->uploads[$tmp];

echo('<img src="uploads/' . $user->name . '/thumbs/' . $upload->file . '?a='.mt_rand(100,9999).'" id="editor_foto" id="' . $upload->id . '">'); 

?>