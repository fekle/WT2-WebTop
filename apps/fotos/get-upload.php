<?php

//error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// common.php includen - da kommen die funktionen rein die immer gebraucht werden;
require_once("../../sys/common.php");
require_once("../../sys/objects.php");
require_once("makethumb.php");

//session, ui und db starten
$session = new Session();
$db = new DB();

//schaun ob user eingeloggt ist
$loggedIn = $session->loggedIn();

$user = $db->userinfo($session->get("user"));

$settings = $db->getSettings();

$uploads = $settings->apps->fotos->uploads;

$uploadDir = "/var/www/fh/webtechnologien/2/WebTop/apps/fotos/uploads/" . $user->name . "/fotos/";

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 52428800) && in_array($extension, $allowedExts)){
	if ($_FILES["file"]["error"] > 0){
		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	}else{

		if (file_exists($uploadDir . $_FILES["file"]["name"])){
			echo $_FILES["file"]["name"] . " already exists. ";
		}
		else{

			move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDir . htmlspecialchars($_FILES["file"]["name"]));
			copy($uploadDir . $_FILES["file"]["name"], $uploadDir . $_FILES["file"]["name"]."_orig");
			$new = new stdClass();

			$date = new DateTime("Europe/Vienna");

			$new->file = htmlspecialchars($_FILES["file"]["name"]);
			$new->name = htmlspecialchars($_FILES["file"]["name"]);
			$new->id = uniqid("webtop_img_".$user->name."_");
			$new->date = $date->format('d.m.Y H:i:s');
			$new->thumb = uniqid("webtop_thumb_".$user->name."_");

			array_push($uploads, $new);

			$settings->apps->fotos->uploads = $uploads;

			$db->saveSettings($settings);

			thumb($user->name, $new->file);
		}
	}
}else{
	echo "Invalid file";
}

?>
