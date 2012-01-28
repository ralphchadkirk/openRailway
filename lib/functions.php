<?php
	function getModuleConfig($directory)
	{
		global $module;
		$path = FROOT . "modules/" . $directory . "/";	
		$module = parse_ini_file($path . "module.cfg");
	}
	
	function doclibBreadcrumbArray($id)
	{
		global $name;
		$name = array();
		if(!isset($id))
		{
			$id = $row['folder_id'];
		}
		$sql = "SELECT * FROM `doclib_folders` WHERE `folder_id` = '" . $id . "'";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		
		if($row['parent_id'] == 0)
		{
			return null;
			die();
		} else
		{
			array_push($name,$row['folder_name']);
		}
	}
?>