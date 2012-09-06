<?php
    include("config.php");
    session_start();
	openRailwayCore::initialisation();
    openRailwayCore::dbConnect();
    if(isset($_POST['username']) && isset($_POST['password']))
    {
    	Authentication::logUserIn($_POST['username'],$_POST['password']);
    }
    if(isset($_GET['logout']))
    {
    	Authentication::logUserOut();
    }
    Authentication::blockPageToVisitors();
    
    // Check to see if a system message is set
	$sysmess = openRailwayCore::getSystemMessage();
	openRailwayCore::pageHeader("Your dashboard");
    openRailwayCore::pageFooter();
?>