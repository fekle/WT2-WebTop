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
	
	$settings = $db->getSettings()->apps->fotos;
	
	$uploads = $settings->uploads;
	
	require_once("../../templates/app_head.php");
?>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href="fotos.css" rel="stylesheet">

<div id="foto_app_header">
	<a href="index.php"><div class="btn_left">
		back
	</div></a>
	Foto-App // Edit 
</div>
<div id="foto_wrapper">
<?php
	
	foreach($uploads as $key=>$upload){
		?>
			<div class="foto_editbox">
				<h2><?php echo $upload->name ?></h2>
		<?php
			echo('<img src="uploads/' . $user->name . '/thumbs/' . $upload->file . '?a='.mt_rand(100,9999).'" class="thumb edit_thumb" id="' . $upload->id . '">');
		?>
				<div class="delete_foto" data-foto="<?php echo $upload->id ?>">
					Delete
				</div>
				<div class="rename_foto" data-foto="<?php echo $upload->id ?>" data-name="<?php echo $upload->name ?>">
					Rename
				</div>
				<div class="edit_foto" data-foto="<?php echo $upload->id ?>">
					Edit
				</div>
				<a href="download.php?id=<?php echo $upload->id ?>"><div class="edit_download" data-foto="<?php echo $upload->id ?>">
					Download
				</div></a>
			</div>
		<?php
	}	
?>
</div>

<script>
	$(".delete_foto").click(function(){
		$.ajax({
			type: "POST",
			url: "delete.php",
			data: {id: $(this).data("foto")}
		}).done(function(antwort){
			window.location.href=window.location.href;
		})
	})
	$(".rename_foto").click(function(){
		var newname=prompt("Name:",$(this).data("name"));
		$.ajax({
			type: "POST",
			url: "rename.php",
			data: {id: $(this).data("foto"), name: newname}
		}).done(function(antwort){
			window.location.href=window.location.href;
		})
	})
	$(".edit_foto").click(function(){
		window.location.href="editor.php?id="+$(this).data("foto");
	})
</script>

</body>
</html>