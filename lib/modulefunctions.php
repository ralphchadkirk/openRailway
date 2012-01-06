<?php
	// Main functions file
	
	function getInstalledModules()
	{
		$path = FROOT . "modules/" . $name[0];
		$results = scandir($path);
		
		// Module Array
		global $names;
		global $links;
		$names = array();
		$links = array();

		foreach ($results as $result) 
		{
			if ($result === '.' or $result === '..') continue;

			if (is_dir($path . '/' . $result)) 
			{
      		  $froot = $path . "/" . $result;
      		  $ini_array = parse_ini_file($froot . "/module.cfg");
      		  array_push($names,$ini_array['name']);
      		  array_push($links,$ini_array['directory']);
    		}
		}
		return $links;
		return $names;
	}
	
	function getModuleConfigArray($directory)
	{
		global $module;
		$path = FROOT . "modules/" . $directory . "/";	
		$module = parse_ini_file($path . "module.cfg");
	}
?>