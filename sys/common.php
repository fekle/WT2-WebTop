<?php

	class Session{
		function __construct(){
			//startet die sesseion (nonane)
			session_start();
		}

		function get($key){
			//returnt den variableninhalt oder false wenn variable nicht definiert.
			if(isset($_SESSION[$key])){
				return $_SESSION[$key];
   			}else{
	   			return false;
   			}
		}

		function getAll(){
			//returnt ein array mit den session daten
			return $_SESSION;
		}

		function set($key, $val){
			//setzt eine variable
			$_SESSION[$key] = $val;
		}

		//checkt ob user eingloggt is
		function loggedIn(){
			if(isset($_SESSION["loggedIn"])){
				if($_SESSION["loggedIn"] == "true"){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}

	class DB{
		public $db;

		function __construct(){
			//öffnet DB verbindung
			//$pw = "PASSWORD";
			//
			//getting pw from secure file - use your own PW!
			$pw = file_get_contents("/var/www/secure/fh-projekte-db-pw.txt");

			$this->db = new PDO('mysql:host=localhost;dbname=fh-webtop;charset=utf8', 'fh-projekte', $pw);
		}

		function get($what, $from, $where, $wherewhat, $single){
			//returnt datenarray
			if(!isset($where)){
				$prep = $this->db->prepare("SELECT ? FROM ?");
				$prep->execute(array($what, $from));
				return $prep->fetchAll(PDO::FETCH_ASSOC);
			}else{
				$prep = $this->db->prepare("SELECT ? FROM ? WHERE ? = ?");
				$prep->execute(array($what, $from, $where, $wherewhat));
				return $prep->fetch(PDO::FETCH_OBJ);
			}
		}

		function saveapp($apps){
			$user = $_SESSION["user"];
			$prep = $this->db->prepare("UPDATE users SET app_save = ? WHERE name = ?");
			$prep->execute(array($apps, $user));
		}

		function get_saveapp(){
			$user = $_SESSION["user"];
			$prep = $this->db->prepare("SELECT app_save FROM users WHERE name = ?");
			$prep->execute(array($user));
			return $prep->fetch(PDO::FETCH_ASSOC);
		}

		function getSettings(){
			$user = $_SESSION["user"];
			$prep = $this->db->prepare("SELECT settings FROM users WHERE name = ?");
			$prep->execute(array($user));
			$get = $prep->fetch(PDO::FETCH_ASSOC);
			return unserialize($get["settings"]);
		}

		function saveSettings($settings){
			$user = $_SESSION["user"];
			$settings = serialize($settings);
			$prep = $this->db->prepare("UPDATE users SET settings = ? WHERE name = ?");
			$prep->execute(array($settings, $user));
		}

		function register($user, $pwd, $name){
			$user = htmlspecialchars($user);
			$pwd = htmlspecialchars($pwd);
			$name = htmlspecialchars($name);
			$user = $_SESSION["user"];
			$prep = $this->db->prepare("INSERT INTO users (name,pwd,full_name) VALUES (?,?,?)");
			$prep->execute(array($user, $pwd, $name, $user));
		}

		//check ob user in DB is und pwd stimmt
		function userCheck($user, $pwd){
			// Nicht mehr nötig, PWD wird beim client verschlüsselt und schon verschlüsselt übertragen :)
			//$pwd = hash("SHA512", md5($pwd));
			$prep = $this->db->prepare("SELECT full_name FROM users WHERE name = ? AND pwd = ?");
			$prep->execute(array($user, $pwd));
			return $prep->fetch(PDO::FETCH_ASSOC);
		}

		function simpleUserCheck($user){
			$prep = $this->db->prepare("SELECT full_name FROM users WHERE name = ?");
			$prep->execute(array($user));
			return $prep->fetch(PDO::FETCH_ASSOC);
		}

		//erstellt die APP - Objekte anhand der APP klasse und den Daten in der Datenbank
		function apps($user){
			$prep = $this->db->prepare("SELECT * FROM apps WHERE user = ? OR user = 'all'");
			$prep->execute(array($user));

			foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $key => $line){
				$apps[$key] = new App($line["name"], $line["full_name"], $line["url"], $line["user"], $line["icon"]);
			}
			return $apps;
		}

		//erstellt nur ein app objekt, sonst gleich wie oben.
		function app($user, $name){
			$prep = $this->db->prepare("SELECT * FROM apps WHERE (user = ? OR user = 'all') AND name = ?");
			$prep->execute(array($user, $name));

			foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $key => $line){
				$app = new App($line["name"], $line["full_name"], $line["url"], $line["user"], $line["icon"]);
			}

			return $app;
		}

		function userinfo($user){
			$prep = $this->db->prepare("SELECT * FROM users WHERE name = ?");
			$prep->execute(array($user));
			$res = $prep->fetch(PDO::FETCH_ASSOC);

			foreach($res as $key => $data){
				$tmp[$key] = $data;
			}

			$user = new User($tmp["name"], $tmp["full_name"], $tmp["pwd"], $tmp["app_save"]);

			return $user;
		}
	}

	class UI{
		//printet den head
		function head($title, $loggedIn){
			require_once("templates/head.php");
		}

		//printet den foot
		function foot(){
			require_once("templates/foot.php");
		}

		//printet das login
		function login(){
			require_once("templates/login.php");
		}

		//..... sollte selbsterklärend sein haha
		function webtop($apps, $user){
			require_once("templates/webtop.php");
		}
	}


	function ldap_login($uid,$pw){
		$ldapserver = "ldap://ldap.technikum-wien.at";
		$searchbase = "ou=People,dc=technikum-wien,dc=at";
		$uid = strtolower($uid);
		$userdn = "uid=".$uid.",".$searchbase;
		$result = new stdClass();

		try{
			$ds=ldap_connect($ldapserver);
			if(!$ds){
				throw new Exception("Error: " . ldap_error($ds) . ".");
			}
			if(!@ldap_bind($ds,$userdn,$pw)){
				throw new Exception("Error: " . ldap_error($ds) . ".");
			}
			$data = ldap_get_entries($ds,ldap_search($ds, $searchbase, "(&(uid=".$uid.")(objectClass=*))"));
			if($data === false){
				throw new Exception("Error: " . ldap_error($ds) . ".");
			}
			$result->success = true;
			$result->uid = $uid;
			$result->vn = $data[0]['givenname'][0];
			$result->nn = $data[0]['sn'][0];
			ldap_close($ds);
		}catch(Exception $e){
			$result->success = false;
			$result->error = $e->getMessage();
		}

		return $result;
	}

?>
