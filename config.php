<?php
// ---------------------------
// USER DEFINED VALUES
// ---------------------------
    define(ROOT,'http://localhost/~RChadkirk/openRailway/');
    define(FROOT,'/Users/RChadkirk/Sites/openRailway/');
    define('DB_HOST','localhost');
    define('DB_NAME','open-railway');
    define('DB_USER','root');
    define('DB_PASS','');
    $railway_name = "Railway Name";
    
// ---------------------------
// TABLE VALUES
// ---------------------------    
    
    define('CONFIG_TABLE','config');
    define('STAFF_MASTER_TABLE','staff_master');
    
    function openRailway_init()
    {
		// Include the required files
		include(FROOT . "lib/core.class.php");
		include(FROOT . "lib/template.php");			
		// Trigger error reports
		error_reporting(E_ALL);
	}
?>