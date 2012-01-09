<?php
	include("../../config.php");
	openRailway_init();
	getModuleConfig("staff");
	page_header("Staff List",$module['name'],$module['directory'],$module['css']);
	
	$template = new Template();
    $template->set_custom_template('html','default');
	$template->set_filenames(array(
                                   'stafflist' => 'stafflist.html'
                                   ));
    $template->display('stafflist');
    
	page_footer();
?>