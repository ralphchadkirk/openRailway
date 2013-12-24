<?php
    include("config.php");
    session_start();
	openRailwayCore::initialisation();
    openRailwayCore::dbConnect();

    Authentication::blockPageToVisitors();
	
	openRailwayCore::pageHeader("Your dashboard");
	$template = new Template;
	$template->set_custom_template("theme/" . STYLE,'default');
	$template->set_filenames(array(
								   'body' => 'home.html'
								   ));
	$template->display('body');

    openRailwayCore::pageFooter();
?>