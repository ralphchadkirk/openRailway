<?php
	class openRailwayCore
	{
		public static function initialisation()
		{
			// Include required files
			include(FROOT . "lib/auth.class.php");
			include(FROOT . "lib/perm.class.php");
			include(FROOT . "lib/temp.class.php");
			include(FROOT . "lib/mailer.class.php");
			error_reporting(E_ALL & ~E_STRICT);
			//set_error_handler("openRailwayCore::error_handler",E_NONE);
			ini_set('log_errors','1');
			if(isset($_SESSION['session_id']))
			{
				Authentication::updateActiveTime($_SESSION['session_id']);
			}
		}
		// Error Handler
		public static function error_handler($errno,$errstr,$errfile,$errline,$errcontext)
		{
			// Display error page
			include("lib/pages/error.php");
			die();
		}
		// Database
		public static function dbConnect()
		{
			$con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
    		if(!$con)
        	{
            	trigger_error("Could not connect to database with credentials supplied in config.php",E_USER_ERROR);
        	}
       		$selected_db = mysql_select_db(DB_NAME);
        	if(!$selected_db)
        	{
            	trigger_error("Database '" . DB_NAME . "' could not be found",E_USER_ERROR);
        	}
		}
		
		public static function dbQuery($query)
		{
   			$result = mysql_query($query);
			if(!$result)
			{
				trigger_error("Query failed: $query",E_USER_ERROR);
			}
  			return $result; 
		}
		
		public static function deleteFrom($table,$wherefield,$operator,$whereparameter)
		{
			$sql = "DELETE FROM `" . $table . "` WHERE `" . $wherefield . "` " . $operator . " '" .$whereparameter . "'";
			$result = mysql_query($sql);
			if(!$result)
			{
				trigger_error("Could not delete record: $sql",E_USER_ERROR);
			}
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
			$template->set_custom_template(FROOT . 'theme/' . STYLE,'default');
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
			if(isset($num))
			{
				$template->assign_var('UNREAD_MESSAGES',$num);
			}
                        
			$template->set_filenames(array(
											'head' => 'header.html',
											));
			$template->display('head');
		}
		
		public static function pageFooter()
		{
			global $railway_name;
			$template = new Template();
			$template->set_custom_template(FROOT . 'theme/' . STYLE,'default');
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
		
		// Config Array
		public static function populateConfigurationArray()
		{
			$result = openRailwayCore::dbQuery("SELECT * FROM " . CONFIG_TABLE);
			$config =  array();
			while($row = mysql_fetch_assoc($result));
			{
				$config = array(
									$row['key'] => $row['value'],
								);
			}
			
			return $config;
		}
		
		// System Messages
		public static function getSystemMessage()
		{
			$sql = "SELECT `value` FROM `" . CONFIG_TABLE . "` WHERE `key` = 'sysmess'";
			$result = openRailwayCore::dbQuery($sql);
			$row = mysql_fetch_assoc($result);
			$sysmess = $row['value'];
			return $sysmess;
		}
	}
?>