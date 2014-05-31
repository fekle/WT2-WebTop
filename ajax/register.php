<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	//common functions
	require_once("../sys/common.php");
	
	$response = new stdClass();
	
	//db aufmachen
	$db = new DB();
	//session laden
	$session = new Session();
	
	$data = json_decode($_POST["data"]);
		
	if($data->fh){
		$ldap = ldap_login($data->user, $data->pwd);
				
		if($ldap->success){
			$check = $db->simpleUserCheck($ldap->uid);
			if(isset($check["full_name"])){
				$response->success = false;
				$response->error = "User already registered";
			}else{
				$response->success = true;
				$response->user = $ldap->uid;
				$response->name = $ldap->vn . " " . $data->nn;
				$db->register($ldap->uid,$data->cryptpwd,$ldap->vn . " " . $ldap->nn);
			}
		}else{
			$response->success = false;
			$response->error = "LDAP: $ldap->error";
		}
	}else{
		if($data->pwd == $data->pwd2){
			$check = $db->simpleUserCheck($data->user);
			if(isset($check["full_name"])){
				$response->success = false;
				$response->error = "User already registered";
			}else{
				$response->success = true;
				$response->user = $data->user;
				$response->name = $data->name;
				$db->register($data->user,$data->pwd,$data->name);	
			}
		}else{
			$response->success = false;
			$response->error = "Passwords not matching.";
		}
	}
	
	echo(json_encode($response));
?>