<?php
    /*  
        File:        dbwrapper.php
        Purpose:     To provide a series of interaction functions with the database for security.
    */

    // Database user values
    define('DB_HOST','localhost');
    define('DB_NAME','open-railway');
    define('DB_USER','root');
    define('DB_PASS','');
    
    // Table values
    define('CONFIG_TABLE','config');
    define('STAFF_MASTER_TABLE','staff_master');
    
    
    // Connect
    function db_connect()
    {
        $con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
        if(!$con)
        {
            error_handle('db','Could not connect to database: ' . mysql_error());
            die();
        }
        $selected_db = mysql_select_db(DB_NAME);
        if(!$selected_db)
        {
            error_handle('db','Cannot find the specified database: ' . mysql_error());
            die();
        }
    }
    
    // Runs query as written
    function db_query($query)
    {
        $result = mysql_query($query);
		while($row = mysql_fetch_assoc($result));
		{
			return $row;
		}
    }
?>