<?php
	session_start();
	class Authentication extends openRailwayCore
	{
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
			$query = "SELECT * FROM `" . USERS_TABLE . "` WHERE `username` = '" . $username . "' AND password = MD5('" . $password . "')";
			$result = openRailwayCore::dbQuery($query);
			if(mysql_num_rows($result) >0)
			{
				$row = mysql_fetch_assoc($result);
				session_regenerate_id();
				$_SESSION['session_id'] = session_id();
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['log_in_time'] = time();
				$_SESSION['staff_id'] = $row['staff_id'];
				$sql = "INSERT INTO " . SESSIONS_TABLE . " VALUES ('" . $_SESSION['session_id'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['user_id'] . "','" . $_SESSION['staff_id'] . "')";
				$result = openRailwayCore::dbQuery($sql);
				header("Location: " . ROOT . "index.php");
				openRailwayCore::logAction($_SESSION['user_id'],"login");
			}
			else 
			{
				openRailwayCore::logAction("","failed-login");
				header("Location: " . ROOT . "index.php?l=fail");
			}
		}
		public static function logUserOut()
		{
			openRailwayCore::deleteFrom(SESSIONS_TABLE,'session_id','=',$_SESSION['session_id']);
			openRailwayCore::logAction($_SESSION['user_id'],"logout");
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
/*		public static function getDetailsOfUserLoggedIn($uid)
		{
			$query = "SELECT * FROM `" . STAFF_MASTER_TABLE . "` WHERE `staff_id` = '" . $uid . "'";
			$result = mysql_query($query);
			return($result);
		} */
	}
?>