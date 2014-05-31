<?php
	$a = new stdClass();

	$s = new stdClass();
	
	$f = new stdClass();
	
	$x = array();
	
	for($i=1;$i<2;$i++){
		$u = new stdClass();
		$u->name = "Foto $i";
		$u->file = sprintf("%03d", $i) .".jpg";
		$u->date = mt_rand(10000,999999);
		$u->thumb = "thumb_$i.jpg";
		$u->id = uniqid("webtop_img_if13b046_");
		$u->etc = "etc...";
		
		$x[$i] = $u;
	}
	
	$f->uploads = $x;
	
	$a->fotos = $f;
	
	$s->apps = $a;

?>

<pre>
Class "Settings": <br>

<?php print_r($s); ?>

<br>

Zum speichern:

<?php print_r(serialize($s)); ?>
</pre>