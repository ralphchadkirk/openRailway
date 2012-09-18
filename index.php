<?php
    include("config.php");
    session_start();
	openRailwayCore::initialisation();
    openRailwayCore::dbConnect();

    Authentication::blockPageToVisitors();
	
	openRailwayCore::pageHeader("Your dashboard");
    openRailwayCore::pageFooter();
?>