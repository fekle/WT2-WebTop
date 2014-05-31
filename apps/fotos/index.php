<?php

	//error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	// common.php includen - da kommen die funktionen rein die immer gebraucht werden;
	require_once("../../sys/common.php");
	require_once("../../sys/objects.php");

	//session, ui und db starten
	$session = new Session();
	$ui = new UI();
	$db = new DB();

	//schaun ob user eingeloggt ist
	$loggedIn = $session->loggedIn();

	$user = $db->userinfo($session->get("user"));

	$settings = $db->getSettings();


	$fresh = false;

	if(!is_object($settings)){
		$settings = new stdClass();
		$settings->apps = new stdClass();
		$settings->apps->fotos = new stdClass();
		$settings->apps->fotos->uploads = array();
		$db->saveSettings($settings);

		mkdir("uploads/" . $user->name . "/fotos", 777, true);
		mkdir("uploads/" . $user->name . "/thumbs", 777, true);


		$fresh = true;
	}elseif(empty($settings->apps->fotos->uploads)){
		$fresh = true;
	}

	$settings = $settings->apps->fotos;

	$uploads = $settings->uploads;

	require_once("../../templates/app_head.php");
?>
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen">
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href="fotos.css" rel="stylesheet">

<div id="foto_app_header">
	<a href="upload.php"><div class="btn_left">
		upload
	</div></a>
	Foto-App
	<?php if(!$fresh){ ?>
	<a href="edit.php"><div class="btn_right">
		edit
	</div></a>
	<?php } ?>
</div>
<div id="foto_wrapper">
<?php

	if(!$fresh){
		foreach($uploads as $key=>$upload){
				echo('<a class="fancybox" rel="group" href="uploads/' . $user->name . '/fotos/' . $upload->file . '?a='.mt_rand(100,9999).'"><img src="uploads/' . $user->name . '/thumbs/' . $upload->file . '?a='.mt_rand(100,9999).'" class="thumb" id="' . $upload->id . '"></a>');
			}
	}else{
		echo('<div id="notofots">No Photos available. Let\'s upload some!</div>');
	}
?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>

</body>
</html>
