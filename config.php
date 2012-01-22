<?php
    define(ROOT,'http://localhost/~RChadkirk/openRailway/');
    define(FROOT,'/Users/RChadkirk/Sites/openRailway/');
    $railway_name = "Railway Name";

// ---------------------------
// DO NOT EDIT BELOW THIS LINE
// ---------------------------
	
	function openRailway_init()
	{
		include(FROOT . "lib/dbwrapper.php");
		include(FROOT . "lib/errorhandler.php");
		include(FROOT . "lib/modulefunctions.php");
		include(FROOT . "lib/template.php");
	}
?>