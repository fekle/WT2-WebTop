<!doctype html>
<html>
	<head>
		<meta name="application-name" charset="WebTop">
		<meta name="author" content="Aleksandar Lepojic, Felix Klein">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="shortcut icon" href="/img/favicon.png">
		<link rel="apple-touch-icon" href="/img/favicon.png">
		<link rel="icon" href="/img/favicon.png" type="image/png">
		<title><?php echo $title ?></title>
		<link rel="stylesheet" href="/css/grid.css">
		<link rel="stylesheet" href="/css/main.css">
		<?php
			//schaut ob loggedin, wenn ja holt er das webtop.css, sonst das login.css, eh klar
			if($loggedIn){
				echo('<link rel="stylesheet" href="/css/webtop.css">');
			}else{
				echo('<link rel="stylesheet" href="/css/login.css">');
			}
		?>
		<link rel="stylesheet" href="/css/jquery-ui.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery-ui.js"></script>
		<script src="/js/jquery-cookie.js"></script>
		<?php
			//schaut ob loggedin, wenn nein dann werden die crypto scripts eingebunden, für die passwortverschlüsselung
			if(!$loggedIn){
				echo('<script src="/js/sha512.js"></script>');
			}
		?>
	</head>
	<body>
