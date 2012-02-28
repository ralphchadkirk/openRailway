<?php
	class Mailer extends openRailwayCore
	{
		public static function mailUser($uid,$subject,$content = null,$file)
		{
			if(isset($content) && isset($file))
			{
				trigger_error("File and content fields are mutually exclusive in mailUser()",E_USER_WARNING);
			}
		}
	}
?>