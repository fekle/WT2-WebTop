<?php
	session_start();
	$_SESSION["stadt"] = $_GET["stadt"];
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once("flexweather.php");

	$weather = new flexweather($_GET["stadt"]);

	if($weather->error){
		echo $rss_url[$rand];
		echo "<hr>ERROR<hr>";
		echo $weather->error;
		die();
	}
?>

<div class="row">
	<div class="small-6 small-offset-3 medium-4 medium-offset-0 large-2 columns">
		<img class="logo" src="<?php echo $weather->icon ?>">
	</div>
	<div class="small-12 medium-8 large-10 columns">
		<div class="row">
			<div class="small-12 columns">
				<h1><?php echo $weather->temp . "°C - " . $weather->status ?></h1>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-6 large-4 columns">
				<select id="stadt">
					<?php
						$cities = file_get_contents("cities.txt");
						$cities = explode("\n", $cities);

						foreach ($cities as $city) {
							$city = trim($city);
							if($city == $_GET["stadt"]){
								$sel = 'selected=""';
							}else{
								$sel = "";
							}
							echo '<option  name="' . $city . '" ' . $sel . '>' . $city . '</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<div class="infos">
					Gefühlte Temperatur: <?php echo $weather->feelslike ?>°C | Luftfeuchtigkeit:  <?php echo $weather->humidity ?> | Wind:  <?php echo $weather->wind ?>km/h | <a href="<?php echo $weather->link ?>" target="_blank">Mehr Infos</a>
				</div>
			</div>
		</div>
	</div>
</div>
