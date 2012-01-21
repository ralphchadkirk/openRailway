<?php
	include("../../config.php");
	openRailway_init();
	getModuleConfig("staff");
	page_header("Staff List",$module['name'],$module['directory'],$module['css']);
	db_connect();
	$template = new Template();
    $template->set_custom_template('html','default');
	$template->set_filenames(array(
                                   'staffprofile' => 'staffprofile.html'
                                   ));
    $template->display('staffprofile');
    
	page_footer();
?>