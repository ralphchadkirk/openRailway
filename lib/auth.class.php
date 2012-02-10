<?php
	session_start();
	class Authentication extends openRailwayCore
	{
		private function genRandomString()
		{
    		$length = 20;
    		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    		$string = "";    
    		for ($p = 0; $p < $length; $p++)
    		{
       			$string .= $characters[mt_rand(0, strlen($characters) -1)];
    		}
   			return $string;
		}
		public static function blockPageToVisitors()
		{
			if(!isset($_SESSION['session_id']))
			{
				openRailwayCore::pageHeader("Access not authorised");
				$template = new Template;
				$template->set_custom_template('theme','default');
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
				$_SESSION['session_id'] = Authentication::genRandomString();
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['log_in_time'] = time();
				$_SESSION['staff_id'] = $row['staff_id'];
				$sql = "INSERT INTO " . SESSIONS_TABLE . " VALUES ('" . $_SESSION['session_id'] . "','" . $_SESSION['log_in_time'] . "','" . $_SESSION['user_id'] . "','" . $_SESSION['staff_id'] . "')";
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
			$sql = "DELETE FROM " . SESSIONS_TABLE . " WHERE `session_id` = '" . $_SESSION['session_id'] . "'";
			$result = openRailwayCore::dbQuery($sql);
			openRailwayCore::logAction($_SESSION['user_id'],"logout");
			session_destroy();
			header("Location: " . ROOT . "index.php?l=logout");
		}
/*		public static function getDetailsOfUserLoggedIn($uid)
		{
			$query = "SELECT * FROM `" . STAFF_MASTER_TABLE . "` WHERE `staff_id` = '" . $uid . "'";
			$result = mysql_query($query);
			return($result);
		} */
	}
?>