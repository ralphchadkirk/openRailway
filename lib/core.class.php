<?php
	class openRailwayCore
	{
		public static function initialisation()
		{
			// Include required files
			include(FROOT . "lib/auth.class.php");
			include(FROOT . "lib/perm.class.php");
			include(FROOT . "lib/temp.class.php");
			error_reporting(E_ALL);
		}
		// Database
		public static function dbConnect()
		{
			$con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
    		if(!$con)
        	{
            	// ERROR
            	die();
        	}
       		$selected_db = mysql_select_db(DB_NAME);
        	if(!$selected_db)
        	{
            	// ERROR
            	die();
        	}
		}
		
		public static function dbQuery($query)
		{
   			$result = mysql_query($query);
  			return $result; 
		}
		
		public static function deleteFrom($table,$wherefield,$operator,$whereparameter)
		{
			if((!isset($table)) && (!isset($wherefield)) && (!isset($whereparameter)) && (!isset($operator)))
			{
				trigger_error('One or more arguments is missing for openRailwayCore::deleteFrom()',E_USER_ERROR);
			   die();
			}
			$sql = "DELETE FROM `" . $table . "` WHERE `" . $wherefield . "` " . $operator . " '" .$whereparameter . "'";
			$result = mysql_query($sql);
		}
		
		
		// Error Handling
		public static function errorHandler($type,$description)
		{
/*        	// Logging the error
       		$errortime = time();
        	$file = FROOT . "lib/errorlog.txt";
        	$fopenfile = fopen($file,'a') or die('Fatal error \n Error logging file cannot be found');
        	$string = $errortime . " | " . $type . " | " . $description . " \n";
        	// Write string to file
        	fwrite($fopenfile,$string);
        	fclose($fopenfile);
        	// Error page
        	die(); */
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
			if(isset($_SESSION['session_id']))
			{
				$query = "SELECT * FROM `" . STAFF_MASTER_TABLE . "` WHERE `staff_id` = '" . $_SESSION['staff_id'] . "'";
				$result = mysql_query($query);
				$row = mysql_fetch_assoc($result);
				$template->assign_block_vars('if_user_logged_in',array(
																		'NAME' => $row['first_name'] . " " . $row['surname'],
																		));
            }
                        
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
			if(isset($_SESSION['session_id']))
			{
				$template->assign_block_vars('if_user_logged_in','');
			}
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
		
		// Action Logger
		public static function logAction($uid = null,$type,$onstaffid = null)
		{
			$sql = "INSERT INTO activity_log(user_id,on_staff_id,time,type,ip) VALUES('" . $uid . "','" . $onstaffid . "','" . time() . "','" . $type . "','" . $_SERVER['REMOTE_ADDR'] . "')";
			$result = openRailwayCore::dbQuery($sql);
		}
		
	}
?>