<?php

	// common.php includen - da kommen die funktionen rein die immer gebraucht werden;
	require_once("../../sys/common.php");
	require_once("../../sys/objects.php");

	//session, ui und db starten
	$session = new Session();
	$db = new DB();

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

	$file="uploads/" . $user->name . "/fotos/" . $uploads[$tmp]->file;
	$filename = 'webtop-download-'.$user->name.'_'.$uploads[$tmp]->name;
	$filename = str_ireplace(".png", "", $filename);
	$filename = str_ireplace(".jpg", "", $filename);
	$filename = str_ireplace(".jpeg", "", $filename);
	$filename = str_ireplace(".gif", "", $filename);
	$filename .= ".".pathinfo($file, PATHINFO_EXTENSION);
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Content-Length: ' . filesize($file));
	readfile($file);
?>
