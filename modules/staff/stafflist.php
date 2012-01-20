<?php
	include("../../config.php");
	openRailway_init();
	getModuleConfig("staff");
	page_header("Staff List",$module['name'],$module['directory'],$module['css']);
	db_connect();
	
	// Get staff details
	$staff_sql = "SELECT * FROM `" . STAFF_MASTER_TABLE . "";  
    $staff_result = mysql_query($staff_sql);
	
	$template = new Template();
    $template->set_custom_template('html','default');
	while($staff = mysql_fetch_assoc($staff_result)):
		$p_dep_sql = "SELECT * FROM `staff_departments_master` WHERE `master_dept_id` = '" . $staff['main_dept'] . "'";
		$p_dep_result = mysql_query($p_dep_sql);
		$main_dept = mysql_fetch_assoc($p_dep_result);
		$s_dep_sql = "SELECT * FROM `staff_departments_sub` WHERE `sub_dept_id` = '" . $staff['sub_depts'] . "'";
		$s_dep_result = mysql_query($s_dep_sql);
		$sub_dept = mysql_fetch_assoc($s_dep_result);
		
   		$template->assign_block_vars('stafflist_loop',array(
   																'ID' => $staff['staff_id'],
   																'FIRSTNAME' => $staff['first_name'],
   																'LASTNAME' => $staff['last_name'],
   																'ADDRESS' => $staff['address'],
   																'TELEPHONE' => $staff['primary_telephone'],
   																'DEPARTMENT' => $main_dept['dept_name'],
   																'SUBDEPARTMENT' => $sub_dept['sub_dept_name'],
   																'EMAIL' => $staff['email'],
   															));
    endwhile;
	$template->set_filenames(array(
                                   'stafflist' => 'stafflist.html'
                                   ));
    $template->display('stafflist');
    
	page_footer();
?>