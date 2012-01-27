<?php
	function getModuleConfig($directory)
	{
		global $module;
		$path = FROOT . "modules/" . $directory . "/";	
		$module = parse_ini_file($path . "module.cfg");
	}
	
?>