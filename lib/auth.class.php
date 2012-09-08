<?php
	session_start();
	/*!
     @class Authentication
     A class that deals with all authentication aspects, such as users logging in, out and securing pages.
     @updated 2012-02-15
     */

	class Authentication extends openRailwayCore
	{
		private static function encryptPassword($password)
		{
			
		}
		private static function checkPassword($password)
		{
			
		}
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
		public static function logUserIn($username,$password)
		{
			openRailwayCore::dbConnect();
			$query = "SELECT * FROM `" . USERS_TABLE . "` WHERE `username` = '" . $username . "' AND password = '" . sha1($password) . "'";
			$result = openRailwayCore::dbQuery($query);
			if(mysql_num_rows($result) >0)
			{
				$row = mysql_fetch_assoc($result);
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
				if(isset($_SERVER['REMOTE_ADDR']))
				{
					$user_ip = $_SERVER['REMOTE_ADDR'];
				} elseif(isset($_SERVER['HTTP_CLIENT_IP']))
				{
					$user_ip = $_SERVER['HTTP_CLIENT_IP'];
				}
				
				$_SESSION['user_ip'] = $user_ip;
				$sql = "INSERT INTO " . SESSIONS_TABLE . " VALUES ('" . $_SESSION['session_id'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['user_id'] . "','" . $_SESSION['staff_id'] . "','" . $_SESSION['user_ip'] . "')";
				$result = openRailwayCore::dbQuery($sql);
				openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),"auth::logUserIn()",$_SESSION['user_id'],5,1,"login");
				header("Location: " . ROOT . "index.php");
			}
			else 
			{
				openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),"auth::logUserIn()",null,4,1,"failed-login");
				header("Location: " . ROOT . "index.php?l=fail");
			}
		}
		public static function logUserOut()
		{
			if(isset($_SESSION['session_id']))
			{
				openRailwayCore::deleteFrom(SESSIONS_TABLE,'session_id','=',$_SESSION['session_id']);
			}
			session_destroy();
			header("Location: " . ROOT . "index.php?l=logout");
		}
		public static function pollInactiveUsers()
		{
			$config = openRailwayCore::populateConfigurationArray();
			if((time() - $_SESSION['last_active']) > $config['user-inactive'])
			{
				Authentication::logUserOut();
			} else
			{
				$_SESSION['last_active'] = time();
			}
		}
		public static function updateActiveTime($sid)
		{
			if(isset($_SESSION))
			{
				//	$sql = "UPDATE " . SESSIONS_TABLE . " SET last_active_time='" . time() . "' WHERE session_id = '" . $sid . "'";
				//openRailwayCore::dbQuery($sql);
			}
		}

		public static function registerUser($sid)
		{
			
		}
		
		public static function deactivateUser($uid)
		{
			openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),"auth::deactivateUser()",$_SESSION['user_id'],5,0,"deactivate-account");
			Authentication::logUserOut();
			Mailer::mailUser($uid,'openRailway for ' . $railway_name . ' Account deactivated','test','test');
			openRailwayCore::deleteFrom(USERS_TABLE,'user_id','=',$uid);
		}
		
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
		// Access level greater than OET given
		public static function accessLevelGreaterThan($level)
		{

		}
		// Only access level given
		public static function accessLevel($level)
		{
			
		}
	}
?>