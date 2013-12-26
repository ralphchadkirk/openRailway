<?php
    include("config.php");
    session_start();
	openRailwayCore::initialisation();
    openRailwayCore::dbConnect();

    Authentication::blockPageToVisitors();
	
	// Process login info section
	$ipAddr = $_SESSION['user_ip'];
	$loginTime = date('d-m-Y H:i:s', $_SESSION['log_in_time']);
	use phpbrowscap\Browscap;
	$bc = new Browscap(FROOT . "cache");
	// $browser = $bc->getBrowser(); COMMENTED OUT AS XAMPP DOES NOT SUPPORT
	
	openRailwayCore::pageHeader("Your dashboard");
	$template = new Template;
	$template->set_custom_template("theme/" . STYLE,'default');
	$template->assign_var('IP_ADDR',$ipAddr);
	$template->assign_var('LOGTIME',$loginTime);
	if(isset($browser['parent']) && isset($browser['platform']))
	{
		$template->assign_var('BRWSR', $browser['parent'] . " on " . $browser['platform']);
	}
	$template->set_filenames(array(
								   'body' => 'home.html'
								   ));
	$template->display('body');

    openRailwayCore::pageFooter();
?>