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
			$query = "SELECT * FROM `users` WHERE `username` = '" . $username . "' AND password = MD5('" . $password . "')";
			$result = mysql_query($query);
			if(mysql_num_rows($result) >0)
			{
				$row = mysql_fetch_assoc($result);
				$_SESSION['session_id'] = Authentication::genRandomString();
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['log_in_time'] = time();
				$sql = "INSERT INTO sessions(session_id,log_in_time,user_id) VALUES (" . $_SESSION['session_id'] . "," . $_SESSION['log_in_time'] . "," . $_SESSION['user_id'] . ")";
				mysql_query($sql);
				header("Location: " . ROOT . "index.php");
			}
			else 
			{
				header("Location: " . ROOT . "index.php?l=fail");
			}
		}
		public static function logUserOut()
		{
			session_destroy();
			header("Location: " . ROOT . "index.php?l=logout");
		}
	}
?>