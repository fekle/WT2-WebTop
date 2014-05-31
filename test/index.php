<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Album</title>
	<link rel="stylesheet" href="main.css" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:200' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>
<body>
	<form action="upload_file.php" method="post" enctype="multipart/form-data">
		<label for="file">Foto zum Upload:</label>
		<input type="hidden" name="MAX_FILE_SIZE" value="35000000">
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Send File">
	</form>
</form>
</body>
</html>