<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	session_start();

	if(!isset($_SESSION["url"])){
		$url = "http://www.theverge.com/rss/frontpage";
	}else{
		$url = $_SESSION["url"];
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>RSS Reader</title>
	<link rel="stylesheet" type="text/css" href="//cdn.felixklein.at/css/flexgrid.css">
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<div class="toload">

	</div>
	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript">
		function load(url){
			$(".toload").animate({opacity: 0}, 300, function(){
				$(".toload").load("ajax.php?url=" + encodeURIComponent(url), function(){
					$(".toload").animate({opacity: 1}, 300);
					$(".post_title").click(function(event) {
						$("#" + $(this).data("id")).slideToggle(300);
					});
					$(".btn").click(function(event) {
						load($("input#url").val());
					});
					$("input#url").keypress(function(e) {
					    if(e.which == 13) {
					       load($("input#url").val());
					    }
					});
				});
			})
		}

		$("document").ready(function(){
			load("<?php echo $url ?>");
		})


	</script>

</body>
</html>
