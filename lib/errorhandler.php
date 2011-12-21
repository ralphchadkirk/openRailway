<?php
    /*  
        File:        errorhandler.php
        Purpose:     To provide error logging and handling functions
    */
    
    function error_handler($type,$description)
    {
        // Display error page here
        
        // Logging the error
        $errortime = date();
        $file = "errorlog.txt";
        $fopenfile = fopen($file,'a') or die('Fatal error\n Error logging file cannot be found');
        $string = $errortime . " " . $type . " " . $description . "\n";
        // Write string to file
        fwrite($fopenfile,$string);
        fclose($fopenfile);
    }
?>