<?php
	// Main functions file
	
	function getInstalledModules()
	{
		$path = FROOT . "modules/" . $name[0];
		$results = scandir($path);
		
		// Module Array
		global $modules;
		$modules = array();

		foreach ($results as $result) 
		{
			if ($result === '.' or $result === '..') continue;

			if (is_dir($path . '/' . $result)) 
			{
      		  $froot = $path . "/" . $result;
      		  $ini_array = parse_ini_file($froot . "/module.cfg");
      		  array_push($modules,$ini_array['name']);
    		}
		}
		
		return $modules;
	}
?>