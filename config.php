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
	define('STYLE','default');
    $railway_name = "Railway Name";
    
// ---------------------------
// TABLE VALUES
// ---------------------------    
    
    define('CONFIG_TABLE','config');
    define('STAFF_MASTER_TABLE','staff_master');
	define('SESSIONS_TABLE','sessions');
	define('USERS_TABLE','users');
	
	include(FROOT . "lib/core.class.php");
?>