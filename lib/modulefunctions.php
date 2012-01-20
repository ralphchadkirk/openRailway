<?php
	// Main functions file
	
	function getInstalledModules()
	{
		$path = FROOT . "modules/";	
		global $names;
		$names = array();
		$dirs = scandir($path);
		foreach($dirs as $dir)
		{
			if(!is_dir($dir))
			{
				array_push($names,$dir);
			}
		}
		return $names;
	}
	
	function getModuleConfig($directory)
	{
		global $module;
		$path = FROOT . "modules/" . $directory . "/";	
		$module = parse_ini_file($path . "module.cfg");
		return $module;
	}
?>