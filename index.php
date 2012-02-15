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
	$sql = "SELECT `value` FROM `" . CONFIG_TABLE . "` WHERE `key` = 'sysmess'";
	$result = openRailwayCore::dbQuery($sql);
	$row = mysql_fetch_assoc($result);
	$sysmess = $row['value'];
    
    // Start output
    openRailwayCore::pageHeader("Home");
    $template = new Template();
    $template->set_custom_template('theme','default');
    // If a system message is set, we'll enable the sysmess block
    if(isset($sysmess))
    {
        $template->assign_block_vars('system_message',array(
                                                            'TEXT' => $sysmess,
                                                           ));
    }
    $template->set_filenames(array(
                                   'body' => 'home.html'
                                   ));
    $template->display('body');
    openRailwayCore::pageFooter();
?>