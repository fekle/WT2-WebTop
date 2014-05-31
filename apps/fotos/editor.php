<?php

	//error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	// common.php includen - da kommen die funktionen rein die immer gebraucht werden;
	require_once("../../sys/common.php");
	require_once("../../sys/objects.php");
	require_once("../../templates/app_head.php");
	
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
	
	
	$settings->apps->fotos->uploads = $uploads;
	
	//$db->saveSettings($settings);
		require_once("../../templates/app_head.php");

?>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href="fotos.css" rel="stylesheet">
<div id="foto_app_header">
	<a href="edit.php"><div class="btn_left">
		back
	</div></a>
	Foto-App // Bildeditor 
</div>
<div class="row">
	<div class="small-12 columns" style="text-align:center" id="fotobox">
		<?php echo('<img src="uploads/' . $user->name . '/thumbs/' . $upload->file . '?a='.mt_rand(100,9999).'" id="editor_foto" id="' . $upload->id . '">'); ?>
	</div>
</div>
<div class="row">
	<div class="small-12 columns">
		<div id="editor_toolbox">
			<div class="editor_button btn_1" id="flip_h">
				Flip Horizontal
			</div>
			<div class="editor_button btn_2" id="flip_v">
				Flip Vertical
			</div>
			<div class="editor_button btn_1" id="rot_l">
				Rotate Left
			</div>
			<div class="editor_button btn_2" id="rot_r">
				Rotate Right
			</div>
			<div class="editor_button btn_1" id="effect_grey">
				Greyscale
			</div>
			<div class="editor_button btn_3" id="effect_poster">
				Posterize
			</div>
			<div class="editor_button btn_3" id="effect_blow">
				Blow
			</div>
			<div class="editor_button btn_3" id="effect_coal">
				Charcoalize
			</div>
			<div class="editor_button btn_2" id="effect_inv">
				Invert
			</div>
			<div class="editor_button" id="crop">
				Crop
			</div>
			<div class="editor_button" id="undo">
				Undo
			</div>
			<div class="editor_button" id="restore">
				Restore Original
			</div>
		</div>
		<div id="edit_crop" style="display:none">
			Left: <input class="crop_input" type="number" id="crop_left" value="0">%<br>
			Right: <input class="crop_input" type="number" id="crop_right" value="0">%<br><br>
			Top: <input class="crop_input" type="number" id="crop_top" value="0">%<br>
			Bottom: <input class="crop_input" type="number" id="crop_bottom" value="0">%<br>
			<div class="editor_button" id="crop_submit">
				Crop
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 columns">
		<div id="wait" style="text-align:center;display:none">
			<p>Processing, please wait...</p>
		</div>
	</div>
</div>
<script>
	var id = "<?php echo($upload->id); ?>"
	
	function reload(b){
		$("#fotobox").load("editor_img.php?id="+b);
		$("#wait").fadeOut(300);
	}
	
	
	$(".editor_button").click(function(){
		var todo = $(this).attr("id");
		if(todo != "crop" && todo != "crop_submit"){
			$("#wait").fadeIn(300);
			$.ajax({
				async: true,
				type: "POST",
				url: "editor_edit.php",
				data: {id: id, toDo: todo}
			}).done(function(antwort){
				console.log(todo + " response: " + antwort);
				reload(id);
			})	
		}else if(todo=="crop"){
			$("#editor_toolbox").hide();
			$("#edit_crop").show();
		}else if(todo=="crop_submit"){
			$("#wait").fadeIn(300);
			var crop = new Object();
			crop.left = $("#crop_left").val();
			crop.right = $("#crop_right").val();
			crop.top = $("#crop_top").val();
			crop.bottom = $("#crop_bottom").val();
			$.ajax({
				async: true,
				type: "POST",
				url: "editor_edit.php",
				data: {id: id, toDo: "crop", vals: JSON.stringify(crop)}
			}).done(function(antwort){
				console.log(todo + " response: " + antwort);
				reload(id);
			})	
		}
	})
</script>