<?php
	function thumb($name, $file){
		$path = "/var/www/fh/webtechnologien/2/WebTop/apps/fotos/uploads/" . $name . "/fotos/";
		$tpath = "/var/www/fh/webtechnologien/2/WebTop/apps/fotos/uploads/" . $name . "/thumbs/";
		$t = new Imagick($path.$file);
		$t->thumbnailImage(512, 512, true);
		$t->writeImage($tpath.$file);
	}
?>