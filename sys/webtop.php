<?php
	
	//holt die infos über den user
	$user = $db->userinfo($session->get("user"));
	
	//holt die apps des users
	$apps = $db->apps($user->name);
	
	//holt das UI
	$ui->webtop($apps,$user);
?>