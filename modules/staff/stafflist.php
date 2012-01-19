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
   		$template->assign_block_vars('stafflist_loop',array(
   																'ID' => $staff['staff_id'],
   																'FIRSTNAME' => $staff['first_name'],
   																'LASTNAME' => $staff['last_name'],
   																'ADDRESS' => $staff['address'],
   																'TELEPHONE' => $staff['primary_telephone'],
   															));
    endwhile;
	$template->set_filenames(array(
                                   'stafflist' => 'stafflist.html'
                                   ));
    $template->display('stafflist');
    
	page_footer();
?>