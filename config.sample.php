<?php
// ---------------------------
// USER DEFINED VALUES
// ---------------------------
    define(ROOT,'');
    define(FROOT,'');
    define('DB_HOST','');
    define('DB_NAME','');
    define('DB_USER','');
    define('DB_PASS','');
	define('STYLE','');
    $railway_name = "";    
// ---------------------------
// TABLE VALUES
// ---------------------------    
    
    define('CONFIG_TABLE','config');
    define('STAFF_MASTER_TABLE','staff_master');
	define('SESSIONS_TABLE','sessions');
	define('USERS_TABLE','users');
	define('ACCESS_TABLE','access_levels');
	define('LOG_TABLE','log');
	
	include(FROOT . "lib/core.class.php");
?>