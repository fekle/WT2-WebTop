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
	
	$id = $_POST["id"];
	
	foreach($uploads as $key=>$upload){
		if($upload->id == $id){
			$tmp = $key;			
			break;
		}
	}
	
	unlink("uploads/" . $user->name . "/fotos/" . $uploads[$tmp]->file);
	unlink("uploads/" . $user->name . "/fotos/" . $uploads[$tmp]->file."_tmp");
	unlink("uploads/" . $user->name . "/thumbs/" . $uploads[$tmp]->file);
	
	unset($uploads[$tmp]);
	$uploads = array_values($uploads);
	
	$settings->apps->fotos->uploads = $uploads;
	
	$db->saveSettings($settings);
?>