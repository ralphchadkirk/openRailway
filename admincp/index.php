<?php
	include("../config.php");
	session_start();
	openRailwayCore::initialisation();
	openRailwayCore::dbConnect();
	// Get the admin module needed. If none, then assume the stats page
	if(isset($_GET['i']))
	{
		$i = $_GET['i'];
	}
	else
	{
		$i = "stats";
	}
?>