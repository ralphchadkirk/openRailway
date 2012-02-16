<?php
	include("config.php");
	session_start();
	openRailwayCore::initialisation();
	openRailwayCore::dbConnect();
	Authentication::blockPageToVisitors();
	if((isset($_GET['mode'])) && (isset($_GET['uid'])))
	{
		trigger_error("UID and MODE are set",E_USER_ERROR);
	}
	elseif((isset($_GET['uid'])))
	{
		if(!is_numeric($_GET['uid']))
		{
			trigger_error("UID parameter should be integer",E_USER_ERROR);
		}
		openRailwayCore::pageHeader("");
		openRailwayCore::pageFooter();
	}
	elseif(isset($_GET['mode']))
	{
		switch($_GET['mode']):
			case "profile":
				openRailwayCore::pageHeader("Your profile");
				openRailwayCore::pageFooter();
			break;
			case "messages":
				openRailwayCore::pageHeader("Your inbox");
				openRailwayCore::pageFooter();
			break;
		endswitch;
	}
	else
	{
		trigger_error("Correct URL paramaters do not exist",E_USER_ERROR);
	}
?>