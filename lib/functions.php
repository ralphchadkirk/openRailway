<?php
	// Main functions file
	
	function getInstalledModules()
	{
		$path = FROOT . "modules/" . $name[0];
		$results = scandir($path);
		
		// Module Array
		global $modules;
		global $links;
		$modules = array();
		$links = array();

		foreach ($results as $result) 
		{
			if ($result === '.' or $result === '..') continue;

			if (is_dir($path . '/' . $result)) 
			{
      		  $froot = $path . "/" . $result;
      		  $ini_array = parse_ini_file($froot . "/module.cfg");
      		  array_push($modules,$ini_array['name']);
      		  array_push($links,$ini_array['directory']);
    		}
		}
		return $links;
		return $modules;
	}
?>