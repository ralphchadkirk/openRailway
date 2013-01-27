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
	else
	{
		$module = "stats";
	}
	if(isset($module))
	{
		switch ($module)
		{
			case "bug":
					$title = "Report a Bug";
					$active_var = "BUG";
				break;
			case "help":
					$title = "Help & Support";
					$active_var = "HELP";
				break;
			default:
					$title = "Statistics";
					$active_var = "STATS";
				break;
		}
		
		// Load layout
		openRailwayCore::pageHeader($title . " | Control Panel");
		$template = new Template;
		$template->set_custom_template("includes/",'default');
		$template->assign_var('ROOT',ROOT);
		$template->assign_var($active_var,"active");
		$template->assign_var('MAIN_TITLE',$title);
		$template->set_filenames(array(
									   'body' => 'layout.html'
									   ));
		$template->display('body');
		openRailwayCore::pageFooter();
	}
?>