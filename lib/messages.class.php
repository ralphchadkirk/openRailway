<?php
	class Messages extends openRailwayCore
	{
		public static function getNumberUnread($uid)
		{
			$sql = "SELECT `to_id` FROM `message_to` WHERE `user_id` = '" . $uid . "' AND `read` = '0'";
			$result = openRailwayCore::dbQuery($sql);
			$number = mysql_num_rows($result);
			return $number;
		}
		
		public static function getInbox($uid)
		{
			// Get message IDs we can se
			$sql = "SELECT `message_id` FROM `message_to` WHERE `user_id` = '" . $uid . "'";
			$result = openRailwayCore::dbQuery($sql);
			$ids = mysql_fetch_assoc($result);
			print_r($ids);
		}
	}
?>