<?php
	class Permissions extends Authentication
	{
		public static function getUserPermissions($uid)
		{
			
		}
		public static function setUserPermission($uid,$pid)
		{
			
		}
		public static function revokeUserPermission($uid,$pid)
		{
			// Query for user perm number here
			// Query to get integer for pid
			$user_perm ^= $perm_bit;
			// Update user perm query here
		}
		public static function isUserAllowed($uid,$pid)
		{
			
		}
		public static function addNewPermission($name)
		{
			
		}
		public static function suspendUser($uid)
		{
			
		}
	}
?>