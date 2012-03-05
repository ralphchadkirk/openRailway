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
	
	// Get staff member's details
	$result = openRailwayCore::dbQuery("SELECT * FROM `" . STAFF_MASTER_TABLE . "` WHERE `staff_id` = '" . $_SESSION['staff_id'] . "'");
	$staff = mysql_fetch_assoc($result);

    // Start output
    openRailwayCore::pageHeader("Home");
    $template = new Template();
    $template->set_custom_template("theme/" . STYLE,'default');
    // If a system message is set, we'll enable the sysmess block
    if(isset($sysmess))
    {
        $template->assign_block_vars('system_message',array(
                                                            'TEXT' => $sysmess,
                                                           ));
    }
	// Display profile
	$template->assign_var('FULL_NAME',$staff['first_name'] . " " . $staff['surname']);
	$template->assign_var('FIRST_NAME',$staff['first_name']);
	$template->assign_var('MIDDLE_NAME',$staff['middle_name']);
	$template->assign_var('SURNAME',$staff['surname']);
	$template->assign_var('ADDRESS',nl2br($staff['address']));
	$template->assign_var('EMAIL',$staff['email']);
	$template->assign_var('HOME_PHONE',$staff['home_phone']);
	$template->assign_var('WORK_PHONE',$staff['work_phone']);
	$template->assign_var('MOBILE_PHONE',$staff['mobile_phone']);
	$template->assign_var('DATE_OF_BIRTH',$staff['date_of_birth']);
	$template->assign_var('STAFF_ID',$staff['staff_id']);
	
	// Dept loop
	$result = openRailwayCore::dbQuery("SELECT * FROM dept_staff_link WHERE `staff_id` = '" . $staff['staff_id'] . "'");
	while($row = mysql_fetch_assoc($result))
	{
		$start = date("d/m/Y",$row['start_time']);
		$dept = openRailwayCore::dbQuery("SELECT * FROM departments WHERE `dept_id` = '" . $row['dept_id'] . "'");
		$dept_row = mysql_fetch_assoc($dept);
		$service = openRailwayCore::timeDiffConv($row['start_time'],time(),true);
		$template->assign_block_vars('dept_loop',array(
													   'DEPARTMENT' => $dept_row['dept_name'],
													   'SERVICE' => $service,
													   'START_TIME' => $start,
													   ));
	}
	// if not member, display that
	if(mysql_num_rows($result) == 0)
	{
		$template->assign_block_vars('no_departments',array());
	}
	// Set file
    $template->set_filenames(array(
                                   'body' => 'home.html'
                                   ));
    $template->display('body');
    openRailwayCore::pageFooter();
?>