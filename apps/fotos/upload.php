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
	
	$settings = $settings->apps->fotos;
	
	$uploads = $settings->uploads;
	
	require_once("../../templates/app_head.php");
?>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href="fotos.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/plupload.full.min.js"></script>
<script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

<div id="foto_app_header">
	Foto-App // Upload
	<a href="index.php"><div class="btn_right">
		back
	</div></a>
</div>
<div id="foto_wrapper">
<div class="row">
	<div class="small-12 columns">
		<div class="box">
			<div class="row">
				<div class="small-12 columns center" style="text-align:center !important">
					<div id="html5_uploader" style="width: 100%;">Your browser doesn't support native upload.</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function() {
	$("#html5_uploader").pluploadQueue({
		// General settings
		runtimes : 'html5',
		url : 'get-upload.php',
		chunk_size : 0,
		unique_names : true,
		
		filters : {
			max_file_size : '50mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"}
			]
		}
	});
});

</script>

</body>
</html>