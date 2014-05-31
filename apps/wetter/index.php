<?php
	session_start();
	if(!$_SESSION["stadt"]){
		$stadt = "Wien";
	}else{
		$stadt = $_SESSION["stadt"];
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>WeatherApp</title>
	<link rel="stylesheet" type="text/css" href="//cdn.felixklein.at/css/flexgrid.css">
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<div class="header">
		<div class="toload"></div>
	</div>
	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript">
		function load(stadt){
			$(".toload").animate({opacity: 0}, 300, function(){
				$(".toload").load("ajax.php?stadt=" + encodeURIComponent(stadt), function(){
					$(".toload").animate({opacity: 1}, 300);
					$("#stadt").change(function() {
						load($("#stadt option:selected").text());
					});
				});
			})
		}

		$("document").ready(function(){
			load("<?php echo $stadt ?>");
		})
	</script>
</body>
</html>
