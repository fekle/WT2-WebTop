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
	
	require_once("../../templates/app_head.php");
?>
	<body>
		<div class="row">
			<div class="small-12 columns">
				<h1>Hallo, <?php echo $user->full_name ?> (<?php echo $user->name ?>)!</h1>
				<p>
					Hier kommen dann einmal Einstellungen, oder so. Eigentlich sollte hier die Option zum ausloggen sein.
				</p>
				<p>
					Warum hier? Wissen wir auch nicht. Darum haben wir sie an einen sinnvolleren Platz verlegt, nämlich ins Startmenü ;-).
				</p>	
			</div>
		</div>	
	</body>
</html>