<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	//common functions
	require_once("../sys/common.php");
	
	//db aufmachen
	$db = new DB();
	//session laden
	$session = new Session();
	
	$data = json_decode($_POST["data"]);
					
	//usercheck funktion aufrufen
	$login = $db->userCheck($data->user, $data->pwd);
	
	// wenn es passt, name und loggedin in die session haun
	if(isset($login["full_name"])){
		$session->set("loggedIn","true");
		$session->set("user",$data->user);
		// true an js zurückgeben
		echo("true");
	}else{
		//wenn es nicht passt, kommt false zurück, damit der ajax script das weiß und eine fehlermeldung anzeigen kann.
		echo("false");
	}

?>