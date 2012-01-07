<?php
    define(ROOT,'http://localhost/~RChadkirk/openRailway/');
    define(FROOT,'/Users/RChadkirk/Sites/openRailway/');
    $railway_name = "Railway Name";

// ---------------------------
// DO NOT EDIT BELOW THIS LINE
// ---------------------------
	
	function openRailway_init()
	{
		include_once(FROOT . "lib/dbwrapper.php");
		include_once(FROOT . "lib/errorhandler.php");
		include_once(FROOT . "lib/modulefunctions.php");
		include_once(FROOT . "lib/template.php");
	}
?>