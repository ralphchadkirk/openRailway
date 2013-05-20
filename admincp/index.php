<?php
	include("../config.php");
	session_start();
	openRailwayCore::initialisation();
	openRailwayCore::dbConnect();
	Authentication::blockPageToVisitors();
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
			case "usr_create":
					$title = "Create a User";
					$active_var = "USR_CREATE";
				break;
			case "usr_manage":
					$title = "Manage Users";
					$active_var = "USR_MANAGE";
				break;
			case "usr_ban":
					$title = "Manage Suspensions";
					$active_var = "USR_BAN";
				break;
			case "usr_sess":
					$title = "View sessions";
					$active_var = "USR_SESS";
				break;
			case "log_act":
					$title = "Activity Logs";
					$active_var = "LOG_ACT";
				break;
			case "log_err":
					$title = "Security Logs";
					$active_var = "LOG_ERR";
				break;
			default:
					$title = "Statistics";
					$active_var = "STATS";
				break;
		}
		
		if(!isset($active_var))
		{
			$active_var = "error";
		}
		
		// Load layout
		openRailwayCore::pageHeader($title . " | Control Panel");
		$template = new Template;
		$template->set_custom_template("includes/",'default');
		$template->assign_var('ROOT',ROOT);
		$template->assign_var($active_var,"active");
		$template->assign_var('MAIN_TITLE',$title);
		$template->set_filenames(array(
									   'layout' => 'layout.html'
									   ));
		$template->display('layout');
		
		if(file_exists(strtolower($active_var) . ".php"))
		{
			include($active_var . ".php");
		} else
		{
			$main = new Template;
			$main->set_custom_template("includes/",'default');
			$main->assign_var('ROOT',ROOT);
			$main->set_filenames(array(
									   'main' => "error.html"
									   ));
			$main->display('main');
		}
		
		openRailwayCore::pageFooter();
	}
?>