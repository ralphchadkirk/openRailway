<?php
	
	/**
	 * Core Application class
	 *
	 * @author Ralph Chadkirk
	 * @package openRailway
	 */
	class openRailwayCore
	{
		/**
		 * Initialises all other classes and global activities
		 */
		public static function initialisation()
		{
			// Include required files
			include(FROOT . "lib/auth.class.php");
			include(FROOT . "lib/temp.class.php");
			error_reporting(E_ALL & ~E_STRICT);
			//set_error_handler("openRailwayCore::error_handler",E_NONE);
			ini_set('log_errors','1');
			if(isset($_SESSION['session_id']))
			{
				Authentication::updateActiveTime($_SESSION['session_id']);
			}
		}
		
		/**
		 * Handles all errors
		 *
		 * @param integer $errno Error level
		 * @param string $errstr Error message
		 * @param string $errfile File that error occured in
		 * @param integer $errline Line that error occured on
		 * @param array $errcontext An array of all variables at the time of the error
		 */
		public static function error_handler($errno,$errstr,$errfile,$errline,$errcontext)
		{
			// Display error page
			include("lib/pages/error.php");
			die();
		}
		
		/**
		 * Connects to the database
		 */
		public static function dbConnect()
		{
			$con = @mysql_connect(DB_HOST,DB_USER,DB_PASS);
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
		
		/**
		 * Executes SQL query
		 *
		 * @param string $query The query to run
		 * @return resource $result The output from the database
		 */
		public static function dbQuery($query)
		{
   			$result = mysql_query($query);
			if(!$result)
			{
				trigger_error("Query failed: $query",E_USER_ERROR);
			}
  			return $result; 
		}
		
		/**
		 * Deletes a row from a table
		 *
		 * @param string $table The name of the table
		 * @param string $wherefield the database field for use in the query
		 * @param string $operator The operator to use between the field and the parameter
		 * @param string $whereparameter The parameter to use
		 */
		public static function deleteFrom($table,$wherefield,$operator,$whereparameter)
		{
			$sql = "DELETE FROM `" . $table . "` WHERE `" . $wherefield . "` " . $operator . " '" . $whereparameter . "'";
			$result = mysql_query($sql);
			if(!$result)
			{
				trigger_error("Could not delete record: $sql",E_USER_ERROR);
			}
		}
		
		/**
		 * Displays the page header
		 *
		 * @param string $title The title of the page to use
		 * @param string $modulename The name of the current module
		 */
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
																		'ACCESS_LEVEL' => $_SESSION['access_level_desc'] . " (" . $_SESSION['access_level'] . ")"
																	   ,
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
			// Bit of a hack below, will get it sorted when pages added
			$currentFile = $_SERVER["PHP_SELF"];
			$parts = Explode('/', $currentFile);
			$active = $parts[count($parts) - 1];
			if($active == "index.php")
			{
				$template->assign_var('ACTIVE','active');
			}
			else
			{
				$template->assign_var('ACTIVE','');
			}
			
			$template->assign_var('DATE',date("l jS F Y"));
     
			$template->set_filenames(array(
											'head' => 'header.html',
											));
			$template->display('head');
		}
		
		/**
		 * Displays the page footer
		 */
		public static function pageFooter()
		{
			global $railway_name;
			$template = new Template();
			$template->set_custom_template(FROOT . 'theme/' . STYLE,'default');
			$template->assign_var('RAILWAY_NAME',$railway_name);
			$template->assign_var('CURRENT_YEAR',gmdate("Y"));
			$template->assign_var('ROOT',ROOT);
			if(isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 9)
			{
				$template->assign_block_vars('if_access_greater_9','');
			}
			$template->set_filenames(array(
											'foot' => 'footer.html',
											));
			$template->display('foot');
		}
		
		/**
		 * Gets the module config
		 *
		 * Retrieves the module config in an array from the CFG file in the given directory
		 *
		 * @param string $directory The directory to look for the CFG file in
		 * @global array $module
		 */
		public static function getModuleConfig($directory)
		{
			global $module;
			$path = FROOT . "modules/" . $directory . "/";
			$module = parse_ini_file($path . "module.cfg");
		}
		
		/**
		 * Logs events
		 *
		 * @param DateTime $eventTimestamp The timestamp of the event
		 * @param string $interactionIdentifier The unique interaction identifer to associate events
		 * @param integer $userIdentity The user ID, if available
		 * @param integer $eventSeverity The event severity
		 * @param bool $securityRelevant If the event is relevant to security or not
		 * @param string $desc A description of the event
		 */
		public static function logEvent($eventTimestamp,$interactionIdentifier,$userIdentity,$eventSeverity,$securityRelevant,$desc)
		{
			$sql = "INSERT INTO " . LOG_TABLE . " (
													log_timestamp,
													event_timestamp,
													interaction_identifier,
													source_ip,
													source_user_agent,
													user_identity,
													event_severity,
													security_relevant,
													description
											)
											VALUES (
													'" . time() . "',
													'" . $eventTimestamp . "',
													'" . $interactionIdentifier . "',
													'" . $_SERVER['REMOTE_ADDR'] . "',
													'" . $_SERVER['HTTP_USER_AGENT'] . "',
													'" . $userIdentity . "',
													'" . $eventSeverity . "',
													'" . $securityRelevant . "',
													'" . $desc . "'
											)";
			$result = openRailwayCore::dbQuery($sql);
		}
		
		/**
		 * Creates the interaction IDs for logEvent()
		 *
		 * @return string $id The interaction ID
		 */
		public static function createInteractionIdentifier()
		{
			$id = md5(time());
			return $id;
		}
		
		/**
		 * Creates a configuration array
		 *
		 * @return array $config The configuration array
		 */
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
		
		/**
		 * Gets the system message
		 *
		 * @return string $sysmess The system message
		 */
		public static function getSystemMessage()
		{
			$sql = "SELECT `value` FROM `" . CONFIG_TABLE . "` WHERE `key` = 'sysmess'";
			$result = openRailwayCore::dbQuery($sql);
			$row = mysql_fetch_assoc($result);
			$sysmess = $row['value'];
			return $sysmess;
		}
		
		/**
		 * Generates a random ID
		 *
		 * @param integer $length The length of the ID required
		 * @return string $string The random ID
		 */
		public static function randomID($length)
		{
			$string = null;
			if(!isset($length))
			{
				trigger_error("You must set a length for the random integer string",E_USER_WARNING);
			} else 
			{
				$i = 0;
			}
			while($i < $length)
			{
				$n = rand(0,9);
				$string .= $n;
				$i++;
			}
			return $string;
		}
		
		/**
		 * Escapes the given input
		 *
		 * @param mixed $input The resource to escape
		 * @return mixed $clean The escaped resource
		 */
		public static function escapeInput($input)
		{
			$st = strip_tags($input);
			$es = mysql_escape_string($st);
			$ht = htmlspecialchars($es);
			return $clean;
		}
		
		/**
		 * Builds the file integrity table
		 *
		 */
		public static function buildFileIntegrity()
		{
			$files = array();
			
			// Extensions to fetch, an empty array will return all extensions
			$ext = array("php","html");
			
			// Directories to ignore, an empty array will check all directories
			$skip = array();
		
			// Build profile
			$dir = new RecursiveDirectoryIterator(FROOT);
			$iter = new RecursiveIteratorIterator($dir);
			while ($iter->valid())
			{
				// Skip unwanted directories
				if (!$iter->isDot() && !in_array($iter->getSubPath(), $skip))
				{
					// get specific file extensions
					if (!empty($ext))
					{
						// PHP 5.3.4: if (in_array($iter->getExtension(), $ext)) {
						if (in_array(pathinfo($iter->key(), PATHINFO_EXTENSION), $ext))
						{
							$files[$iter->key()] = hash_file("sha1", $iter->key());
						}
					}
					else {
						// ignore file extensions
						$files[$iter->key()] = hash_file("sha1", $iter->key());
					}
				}
				$iter->next();
			}
			
			// Add hashes to databases
			openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),null,5,1,"File integrity hash table built");
			foreach($files as $k => $v)
			{
				$sql = "INSERT INTO integrity_hashes (file_path,file_hash) VALUES ('" . $k . "','" . $v . "')";
				openRailwayCore::dbQuery($sql);
			}
		}
        
        /**
         * Checks file integrity
         *
         * @param string $file The filename or filepath to check. Leave blank to check entire system
         *
         */
        public static function checkFileIntegrity($file)
        {
            
        }
	}
?>