<?php
error_reporting(E_ALL);
ini_set('display-errors', 1);

$allowedExts = array("gif", "jpeg", "jpg", "png", "zip");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 35000000) && in_array($extension, $allowedExts)){

	if ($_FILES["file"]["error"] > 0){
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	}else{
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "Stored in: " . $_FILES["file"]["tmp_name"];
	}
}else{
	echo "Fehler beim Upload!<br><br>Zugelassene Typen sind:<br>'.gif'<br>'.jpeg'<br>'.jpg'<br>'.png'";
}

$fileupload = $_FILES["file"];
$uploaddir = "/var/www/fh/webtechnologien/2/WebTop/test/uploads/";
$uploadfile = $uploaddir . basename($_FILES["file"]["name"]);


if(!$fileupload["error"] && is_uploaded_file($fileupload["tmp_name"])){
	move_uploaded_file($_FILES["file"]["tmp_name"], $uploadfile);
}

?>
