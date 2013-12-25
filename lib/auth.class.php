<?php
	
	/**
	 * User authentication class
	 * @author Ralph Chadkirk
	 * @package openRailway
	 */
	class Authentication extends openRailwayCore
	{
		/**
		 * Starts the session
		 */
		function __construct()
		{
			session_start();
		}
		
		/**
		 * Encrypts a given password
		 *
		 * @param string $password The given password
		 */
		private static function encryptPassword($password)
		{
			
		}
		
		/**
		 * Encrypts a given password
		 *
		 * @param string $password The password to check
		 */
		private static function checkPassword($password)
		{
			
		}
		
		public static function checkIPLocation($ip)
		{
			//check, if the provided ip is valid
			if(!filter_var($ip, FILTER_VALIDATE_IP))
			{
				throw new InvalidArgumentException("IP is not valid");
			}
			
			//contact ip-server
			$response=@file_get_contents('http://www.netip.de/search?query='.$ip);
			if (empty($response))
			{
				throw new InvalidArgumentException("Error contacting Geo-IP-Server");
			}
			
			//Array containing all regex-patterns necessary to extract ip-geoinfo from page
			$patterns=array();
			$patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
			$patterns["country"] = '#Country: (.*?)&nbsp;#i';
			$patterns["state"] = '#State/Region: (.*?)<br#i';
			$patterns["town"] = '#City: (.*?)<br#i';
			
			//Array where results will be stored
			$ipInfo=array();
			
			//check response from ipserver for above patterns
			foreach ($patterns as $key => $pattern)
			{
				//store the result in array
				$ipInfo[$key] = preg_match($pattern,$response,$value) && !empty($value[1]) ? $value[1] : '';
			}
			
			return $ipInfo;
		}
		
		private static function blockPageToUser()
		{
			openRailwayCore::pageHeader("Access denied");
			$template = new Template;
			$template->set_custom_template(FROOT. 'theme/' . STYLE,'default');
			$template->set_filenames(array(
											'body' => 'noaccess.html'
										   ));
			$template->display('body');
			openRailwayCore::pageFooter();
			die();
		}
		
		/**
		 * Locks page to non-authenticated browsers
		 *
		 */
		public static function blockPageToVisitors()
		{
			openRailwayCore::dbConnect();
			if(isset($_SESSION['session_id']))
			{
				$result = openRailwayCore::dbQuery("SELECT `session_id` FROM " . SESSIONS_TABLE . " WHERE `session_id` = '" . $_SESSION['session_id'] . "'");
				if(mysql_num_rows($result) == 0)
				{
					goto login;
				}
			}
			if(!isset($_SESSION['session_id']))
			{
				login:
				openRailwayCore::pageHeader("Access not authorised");
				$template = new Template;
				$template->set_custom_template(FROOT . 'theme/' . STYLE,'default');
				if((isset($_GET['l'])) && ($_GET['l'] == 'fail'))
				{
					$template->assign_block_vars('if_login_failed',array());
				}
				if((isset($_GET['l'])) && ($_GET['l'] == "logout"))
				{
					$template->assign_block_vars('if_logged_out',array());
				}
				if((isset($_GET['l'])) && ($_GET['l'] == "flogout"))
				{
					$template->assign_block_vars('if_force_logged_out',array());
				}
				if((isset($_GET['l'])) && ($_GET['l'] == 'reauth'))
				{
					$template->assign_block_vars('if_reauth',array());
				} else
				{
					$template->assign_block_vars('if_not_reauth',array());
				}
				$template->assign_var('ROOT',ROOT);
				$template->set_filenames(array(
                                   				'body' => 'login.html'
                                   			));
                $template->display('body');
                openRailwayCore::pageFooter();
                die();
			}
		}
				
		/**
		 * Logs a user in, checks if account activated
		 * @param string $username The provided username
		 * @param string $password The provided password
		 */
		public static function logUserIn($username,$password)
		{
			openRailwayCore::dbConnect();
			$query = "SELECT * FROM `" . USERS_TABLE . "` WHERE `username` = '" . $username . "' AND password = '" . sha1($password) . "'";
			$result = openRailwayCore::dbQuery($query);
			if(mysql_num_rows($result) >0)
			{
				$row = mysql_fetch_assoc($result);
				// Make sure accound is not suspended
				if($row['suspended'] == true)
				{
					$_SESSION['user_id_suspended'] = $row['user_id'];
					openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$row['user_id'],5,1,"User (UID: " . $row['user_id'] . ") tried to login whilst suspended");
					header("Location: " . ROOT . "user.php?mode=suspended");
					die();
				}
				// Make sure account is activated, if not, go to activation page
				if($row['activated'] == false)
				{
					header("Location: " . ROOT . "user.php?mode=activate");
					die();
				}
				$query = "SELECT `level_description` FROM `" . ACCESS_TABLE . "` WHERE `access_level` = '" . $row['access_level'] . "'";
				$result = openRailwayCore::dbQuery($query);
				$access = mysql_fetch_assoc($result);
				session_regenerate_id();
				$_SESSION['session_id'] = session_id();
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['log_in_time'] = time();
				$_SESSION['staff_id'] = $row['staff_id'];
				$_SESSION['access_level'] = $row['access_level'];
				$_SESSION['access_level_desc'] = $access['level_description'];
				$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				if(isset($_SERVER['REMOTE_ADDR']))
				{
					$user_ip = $_SERVER['REMOTE_ADDR'];
				} elseif(isset($_SERVER['HTTP_CLIENT_IP']))
				{
					$user_ip = $_SERVER['HTTP_CLIENT_IP'];
				}
				
				$_SESSION['user_ip'] = $user_ip;
				$sql = "INSERT INTO " . SESSIONS_TABLE . " VALUES ('" . $_SESSION['session_id'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['user_id'] . "','" . $_SESSION['staff_id'] . "','" . $_SESSION['user_ip'] . "','" . $_SESSION['user_agent'] . "','" . $_SESSION['access_level'] . "')";
				$result = openRailwayCore::dbQuery($sql);
				openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],5,1,"User (UID: " .$_SESSION['user_id'] . ") logged in");
				header("Location: " . ROOT . "index.php");
			}
			else 
			{
				openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),null,4,1,"Failed login attempt");
				header("Location: " . ROOT . "user.php?mode=auth&action=login&l=fail");
			}
		}
		
		/**
		 * Logs the current user out
		 */
		public static function logUserOut($uid = null)
		{
			if(isset($uid))
			{
				openRailwayCore::deleteFrom(SESSIONS_TABLE,'user_id','=',$uid);
				openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],5,1,"User (UID: " .$uid . ") forced log out");
			}
			if(isset($_SESSION['session_id']) && !isset($uid))
			{
				openRailwayCore::deleteFrom(SESSIONS_TABLE,'session_id','=',$_SESSION['session_id']);
				session_destroy();
				header("Location: " . ROOT . "user.php?mode=auth&action=login&l=logout");
			}
		}

		/**
		 * Updates the user last active timestamp
		 */
		public static function updateActiveTime()
		{
			if(isset($_SESSION))
			{
				openRailwayCore::dbConnect();
				$sql = "UPDATE " . SESSIONS_TABLE . " SET last_active_time = '" . time() . "' WHERE session_id = '" . $_SESSION['session_id'] . "'";
				$result = openRailwayCore::dbQuery($sql);
				$_SESSION['last_active'] = time();
				
				if((time() - $_SESSION['last_active']) > USER_INACTIVE)
				{
					Authentication::logUserOut($_SESSION['user_id']);
				}
			}
		}

		/**
		 * Registers a new user
		 * @param integer $sid The staff record ID to generate a user from
		 * @todo Complete
		 */
		public static function registerUser($sid)
		{
			
		}
		
		/**
		 * Deactivates a user
		 * @param integer $uid The user ID to delete
		 */
		public static function deactivateUser($uid)
		{
			openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],5,0,"User account deactivated");
			Authentication::logUserOut();
			openRailwayCore::deleteFrom(USERS_TABLE,'user_id','=',$uid);
		}
		
		/**
		 * Activates a user
		 * @param string $token The user activation token
		 */
		public static function activateUser($token)
		{
			$query = "SELECT * FROM " . USERS_TABLE . " WHERE `activation_key` = '" . $token . "'";
			$result = openRailwayCore::dbQuery($query);
			$row = mysql_fetch_assoc($result);
			if(mysql_num_rows($result) == 0)
			{
				header("Location: " . ROOT . "user.php?mode=activate&l=fail");
			} elseif(isset($row['user_id']))
			{
				// Activate user
				$query = "UPDATE " . USERS_TABLE . " SET `activated` = '1' WHERE `user_id` = '" . $row['user_id'] . "'";
				$result = openRailwayCore::dbQuery($query);
				header("Location: " . ROOT . "index.php?l=reauth");
				
				openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),null,4,1,"User (UID:" . $row['user_id'] .  ") account activated");
				
				// Get Staff Member details
				$query = "SELECT * FROM " . STAFF_MASTER_TABLE . " WHERE `staff_id` = '" . $row['staff_id'] . "'";
				$result = openRailwayCore::dbQuery($query);
				$staff = mysql_fetch_assoc($result);
				
				// Get Access Level Desc
				$query = "SELECT * FROM " . ACCESS_TABLE . " WHERE `access_level` = '" . $row['access_level'] . "'";
				$result = openRailwayCore::dbQuery($query);
				$access = mysql_fetch_assoc($result);
				
				// Alert user of activation
				$template = new Template;
				$template->set_custom_template("lib/emails",'default');
				$template->assign_var('URL',ROOT);
				$template->assign_var('NAME',$staff['first_name'] . " " . $staff['surname']);
				$template->assign_var('USERNAME',$row['username']);
				$template->assign_var('ACCESS_LEVEL',$access['level_description']);
				$template->assign_var('LEVEL',$row['access_level']);
				$template->set_filenames(array(
											   'email' => 'after-activation.txt'
											   ));
			echo	mail($staff['email'],"openRailway Account Activated",$template->display('email'),"From: no-reply@openrailway");
			} else
			{
				header("Location: " . ROOT . "user.php?mode=activate&l=fail");
			}
		}
		
		/**
		 * Allows usage to specific access levels
		 * @param integer $level The given access level
		 * @param
		 */
		public static function accessLevelController($level,$operator)
		{
			switch($operator)
			{
				case ">":
					if($level > $_SESSION['access_level'])
					{
						Authentication::blockPageToUser();
						openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],5,1,"User " . $_SESSION['user_id'] . "tried to access page without correct permission level");
						die();
					}
				break;
			}
		}
		
		/**
		 * Suspends a user account
		 * @param integer $uid The user account to suspend
		 */
		public static function suspendUser($uid)
		{
			$sql = "UPDATE `users` SET `suspended` = '1' WHERE user_id = '" . $uid . "'";
			$result = openRailwayCore::dbQuery($sql);
			
			openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],5,1,"User " . $uid . " suspended by user " . $_SESSION['user_id']);
		}
		
		/**
		 * Reinstates a user account
		 * @param integer $uid The user account to reinstate
		 */
		public static function reinstateUser($uid)
		{
			$sql = "UPDATE `users` SET `suspended` = '0' WHERE user_id = '" . $uid . "'";
			$result = openRailwayCore::dbQuery($sql);
			
			openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],5,1,"User " . $uid . " reinstated by user " . $_SESSION['user_id']);
		}
		
		public static function requireReAuth()
		{
			
		}
	}
?>