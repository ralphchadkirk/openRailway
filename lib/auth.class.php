<?php
	class Authentication extends openRailwayCore
	{
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
		public static function logUserIn()
		{
		}
		public static function logUserOut()
		{
		}
	}
?>