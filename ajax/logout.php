<?php

	//common functions einbinden
	require_once("../sys/common.php");
	
	//session laden
	$session = new Session();
	
	//loggedIn auf false setzen
	$session->set("loggedIn", "false");
	
	//debug
	//echo($session->get("loggedIn"));
?>