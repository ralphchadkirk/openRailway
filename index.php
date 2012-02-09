<?php
    include("config.php");
    session_start();
    openRailway_init();
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
	
	// Get staff profile
	$query = "SELECT * FROM `staff_master` WHERE `staff_id` = '" . $_SESSION['staff_id'] . "'";
    $result = openRailwayCore::dbQuery($query);
    $staff = mysql_fetch_assoc($result);
    $dobunix = strtotime($staff['date_of_birth']);
    $dob = date("d/m/Y",$dobunix);
    
    
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
    $template->assign_var('FULL_NAME',$staff['first_name'] . " " . $staff['middle_name'] . " " . $staff['surname']);
    $template->assign_var('ADDRESS',nl2br($staff['address']));
    $template->assign_var('DOB',$dob);
    $template->set_filenames(array(
                                   'body' => 'home.html'
                                   ));
    $template->display('body');
    openRailwayCore::pageFooter();
?>