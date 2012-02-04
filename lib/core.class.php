<?php
	class openRailwayCore
	{
		// Database
		public static function dbConnect()
		{
			$con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
    		if(!$con)
        	{
            	errorHandler('db','Could not connect to database: ' . mysql_error());
            	die();
        	}
       		$selected_db = mysql_select_db(DB_NAME);
        	if(!$selected_db)
        	{
            	errorHandler('db','Cannot find the specified database: ' . mysql_error());
            	die();
        	}
		}
		
		public static function dbQuery($query)
		{
		}
		
		// Error Handling
		public static function errorHandler($type,$description)
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
		
		// Templates
		public static function pageHeader($title,$modulename = null)
		{
		    global $railway_name;
            global $names;
                  		
            if(isset($modulename))
            {
            	$t = $title . " - " . $modulename;
            } else
            {
                $t = $title;
            }
                  		
            $template = new Template();
            $template->set_custom_template(FROOT . 'theme','default');
            $template->assign_var('TITLE',$t);
            $template->assign_var('RAILWAY_NAME',$railway_name);
            $template->assign_var('ROOT',ROOT);
                        
            // Display list of modules in /modules
            $path = FROOT . "modules/";	
			$names = array();
			$dirs = scandir($path);
			foreach($dirs as $dir)
			{
				if(!is_dir($dir))
				{
					array_push($names,$dir);
				}
			}
            foreach($names as $name)
            {
                // Get the module config details
				$path = FROOT . "modules/" . $name . "/";	
				$module = parse_ini_file($path . "module.cfg");
                $template->assign_block_vars("module_loop",array(
                  		  											"MODULE_NAME" => $module['name'],
                		  											"MODULE_LINK" => ROOT . "modules/" . $module['directory'] . "/" . $module['landingpage'],
                       	 										));
            }

            $template->assign_var('DATE',date("l jS F Y"));
                        
            $template->set_filenames(array(
                                            'head' => 'header.html',
                                         ));
            $template->display('head');
		}
		
		public static function pageFooter()
		{
			global $railway_name;
            $template = new Template();
            $template->set_custom_template(FROOT .'theme','default');
            $template->assign_var('RAILWAY_NAME',$railway_name);
            $template->assign_var('CURRENT_YEAR',gmdate("Y"));
            $template->set_filenames(array(
                                            'foot' => 'footer.html',
                                        	));
            $template->display('foot');
		}
		
		// Modules
		public static function getModuleConfig($directory)
		{
			global $module;
			$path = FROOT . "modules/" . $directory . "/";
			$module = parse_ini_file($path . "module.cfg");
		}
		
	}
?>