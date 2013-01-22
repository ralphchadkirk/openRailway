<?php
	include("../config.php");
	session_start();
	openRailwayCore::initialisation();
	openRailwayCore::dbConnect();
	// Get the admin module needed. If none, then assume the stats page
	if(isset($_GET['module']))
	{
		$module = $_GET['module'];
	}
	if(isset($module))
	{
		switch ($module)
		{
			case "logs":
					openRailwayCore::pageHeader("Activity Logs | Control Panel");
					include(FROOT . "admincp/includes/layout.html");
					openRailwayCore::pageFooter();
				break;
			default:
				break;
		}
	}
?>