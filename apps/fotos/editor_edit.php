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

	$id = $_POST["id"];
	$toDo = $_POST["toDo"];

	foreach($uploads as $key=>$upload){
		if($upload->id == $id){
			$tmp = $key;
			break;
		}
	}

	$foto = $uploads = $settings->apps->fotos->uploads[$tmp];


	$path = "/var/www/fh/webtechnologien/2/WebTop/apps/fotos/uploads/" . $user->name . "/fotos/";
	$bild = $path . $foto->file;

	function crop($imagick, $gcrop){
		$crop = json_decode($gcrop);
		$width = $imagick->getImageWidth();
		$height = $imagick->getImageHeight();
		$_w = $width - ($width * ((100-$crop->left) / 100));
		$_ww = $width - ($width * ((100-$crop->right) / 100));
		$_h = $height - ($height * ((100-$crop->top) / 100));
		$_hh = $height - ($height * ((100-$crop->bottom) / 100));
		$_tw = $width - $_w - $_ww;
		$_th = $height - $_h - $_hh;

		$imagick->cropImage($_tw, $_th, $_w, $_h);
	}

	function undo($bildl){
		unlink($bildl);
		copy($bildl."_tmp", $bildl);
	}

	function my_restore($bildla){
		unlink($bildla);
		copy($bildla."_orig", $bildla);
	}

	function flip($imagick, $d){
		if($d == "v"){
			$imagick->flopImage();
		}else if($d = "h"){
			$imagick->flipImage();
		}
	}

	function effect($imagick, $e){
		if($e == "grey"){
			$imagick->setImageColorspace(1);
			$imagick->setImageColorspace(2);
		}else if($e == "poster"){
			$imagick->blurImage(10,10);
			$imagick->posterizeImage(4, true);
		}else if($e == "inv"){
			$imagick->negateImage(false);
		}else if($e == "coal"){
			$imagick->contrastImage(1);
			$imagick->contrastImage(1);
			$imagick->contrastImage(1);
			$imagick->setImageColorspace(2);
			$imagick->charcoalImage(8,3);
		}else if($e == "blow"){
			$imagick->modulateImage(200,200,100);
			$imagick->blurImage(20,20);
			for($i=0;$i<4;$i++){
				$imagick->contrastImage(1);
			}
		}
	}

	function rot($imagick, $d){
		if($d == "r"){
			$imagick->rotateImage(new ImagickPixel('#00000000'),90);
		}else if($d = "l"){
			$imagick->rotateImage(new ImagickPixel('#00000000'),-90);
		}
	}

	$imagick = new Imagick($bild);
	switch($toDo){
		case "flip_h":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			flip($imagick, "h");
			break;
		case "flip_v":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			flip($imagick, "v");
			break;
		case "rot_r":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			rot($imagick, "r");
			break;
		case "rot_l":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			rot($imagick, "l");
			break;
		case "effect_grey":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			effect($imagick, "grey");
			break;
		case "effect_poster":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			effect($imagick, "poster");
			break;
		case "effect_inv":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			effect($imagick, "inv");
			break;
		case "effect_coal":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			effect($imagick, "coal");
			break;
		case "effect_blow":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			effect($imagick, "blow");
			break;
		case "crop":
			copy($path . $foto->file, $path . $foto->file."_tmp");
			crop($imagick, $_POST["vals"]);
			break;
		case "undo":
			undo($bild);
			break;
		case "restore":
			my_restore($bild);
			break;

		default:
			// do nothin'
	}

	if($toDo != "undo" && $toDo != "restore"){
		$imagick->writeImage($bild);
	}

	thumb($user->name, $foto->file);
?>
