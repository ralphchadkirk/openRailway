<?php
	class Authentication extends openRailwayCore
	{
		private function genRandomString()
		{
    		$length = 10;
    		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    		$string = ”;    
    		for ($p = 0; $p < $length; $p++)
    		{
       			$string .= $characters[mt_rand(0, strlen($characters))];
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
			$query = "SELECT `user_id` `username` `password` FROM `users` WHERE `username` = '" . $username . "' AND `password` = MD5(" . $password . ")";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			if($username == $row['username'] && $password == $row['password'])
			{
				$_SESSION['session_id'] = genRandomString();
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['log_in_time'] = time();
				$query = "INSERT INTO sessions(session_id,log_in_time,user_id) VALUES (" . $_SESSION['session_id'] . "," . $_SESSION['log_in_time'] . "," . $_SESSION['user_id'] . ")";
				$result = mysql_query($query);
				header("Location: " . ROOT . "index.php");
			}
			else 
			{
				header("Location: " . ROOT . "index.php?l=fail");
			}
		}
		public static function logUserOut()
		{
		}
	}
?>