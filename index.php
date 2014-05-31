<?php

	//     ____                            _         _      _     ____    ___   _  _  _
	//    / ___| ___   _ __   _   _  _ __ (_)  __ _ | |__  | |_  |___ \  / _ \ / || || |
	//   | |    / _ \ | '_ \ | | | || '__|| | / _` || '_ \ | __|   __) || | | || || || |_
	//   | |___| (_) || |_) || |_| || |   | || (_| || | | || |_   / __/ | |_| || ||__   _|
	//    \____|\___/ | .__/  \__, ||_|   |_| \__, ||_| |_| \__| |_____| \___/ |_|   |_|
	//       _     _  |_|   _ |___/           |___/      _                _                         _  _
	//      / \   | |  ___ | | __ ___   __ _  _ __    __| |  __ _  _ __  | |     ___  _ __    ___  (_)(_)  ___
	//     / _ \  | | / _ \| |/ // __| / _` || '_ \  / _` | / _` || '__| | |    / _ \| '_ \  / _ \ | || | / __|
	//    / ___ \ | ||  __/|   < \__ \| (_| || | | || (_| || (_| || |    | |___|  __/| |_) || (_) || || || (__
	//   /_/   \_\|_| \___||_|\_\|___/ \__,_||_| |_| \__,_| \__,_||_|    |_____|\___|| .__/  \___/_/ ||_| \___|
	//    _____      _  _         _  __ _        _                                   |_|         |__/
	//   |  ___|___ | |(_)__  __ | |/ /| |  ___ (_) _ __
	//   | |_  / _ \| || |\ \/ / | ' / | | / _ \| || '_ \
	//   |  _||  __/| || | >  <  | . \ | ||  __/| || | | |
	//   |_|   \___||_||_|/_/\_\ |_|\_\|_| \___||_||_| |_|
	//

	//error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	// common.php requiren - da kommen die funktionen rein die immer gebraucht werden;
	require_once("sys/common.php");
	require_once("sys/objects.php");

	//session, ui und db starten
	$session = new Session();
	$ui = new UI();
	$db = new DB();

	//schaun ob user eingeloggt ist
	$loggedIn = $session->loggedIn();

	//head ausgeben
	$ui->head("WebTop", $loggedIn);


	if($loggedIn){
		//wenn eingeloggt webtop anzeigen
		require_once("sys/webtop.php");
	}else{
		if(isset($_COOKIE["webtop-lepojic_klein-loggedin"])) {
			if($_COOKIE["webtop-lepojic_klein-loggedin"] != null && $_COOKIE["webtop-lepojic_klein-loggedin"] != "" && $_COOKIE["webtop-lepojic_klein-loggedin"] != NULL && $_COOKIE["webtop-lepojic_klein-loggedin"] != "null"){
				$cuser = $_COOKIE["webtop-lepojic_klein-loggedin"];
				$ckey = $_COOKIE["webtop-lepojic_klein-loggedin-key"];

				$login = $db->userCheck($cuser, $ckey);

				if(isset($login["full_name"])){
					$session->set("loggedIn","true");
					$session->set("user",$cuser);

					echo('<meta http-equiv="refresh" content="0">');
				}else{
					$ui->login();
				}
			}else{
				$ui->login();
			}
		}else{
			$ui->login();
		}
	}

	//foot ausgeben
	$ui->foot();
?>
