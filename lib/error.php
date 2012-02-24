<!DOCTYPE html>
<html>
	<head>
		<title>Sorry, an error has occured | openRailway</title>
		<style>
html {
	background: #E5E7E1;
}
p, h1 {
	font-family: Arial;
}
h1 {
	color: red;
}
p {
	font-size: 80%;
}
#error {
	border: solid red 1px;
	margin: auto;
	width: 50%;
	padding: 5px;
	margin-top: 50px;
	background: #FFBAD2;
}
		</style>
	</head>
	<body>
		<div id="error">
			<h1>Internal Server Error (500)</h1>
			<p>Sorry, an internal server error has occured, meaning openRailway cannot generate the page you requested.<p>
			<p>Did you follow a valid link to this page? If so, please contact your openRailway administrator.</p>
			<p>The details of the error are as follows:</p>
			<pre>
Numb: <?php echo $errno . " \n" ?>
Line: <?php echo $errline . " \n" ?>
File: <?php echo $errfile . " \n" ?>
<?php echo $errstr . " \n" ?>
			</pre>
		</div>
	</body>
</html>