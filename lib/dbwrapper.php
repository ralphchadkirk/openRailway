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
    define('','');
    
    // Database Functions
    
    // Connect
    function db_connect()
    {
        $con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
        if(!$con)
        {
            error_handle('db','Could not connect to database: ' . mysql_error());
            die();
        }
    }
    
    // Runs query as written
    function db_query($query)
    {
        mysql_select_db(DB_NAME,$con);
        $query_clean = mysql_real_escape_string($query);
        $result = mysql_query($query_clean);
        $row = mysql_fetch_assoc($result);
        return $row;
    }
    
    // Close connection
    function db_close()
    {
        mysql_close($con);
    }
?>