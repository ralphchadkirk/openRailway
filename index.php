<?php
    include("config.php");
    include("lib/dbwrapper.php");
    include("lib/errorhandler.php");
    include("lib/template.php");
    include("lib/functions.php");
    db_connect();
    
    // Check to see if a system message is set
    $sql = "SELECT `value` FROM `" . CONFIG_TABLE . "` WHERE `key` = 'sysmess'";
//    db_query($sql);
//    $sysmess = $row['value'];
    
    $result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$sysmess = $row['value'];
    
    page_header("Home");
    $template = new Template();
    $template->set_custom_template('theme','default');
    // If a system message is set, we'll enable the sysmess block
    if(isset($sysmess))
    {
        $template->assign_block_vars('system_message',array(
                                                            'TEXT' => $sysmess,
                                                           ));
    }
    
    // Check for avaliable modules and display
    getInstalledModules();
	foreach($modules as $key=>$modulename)
	{
		foreach($links as $key=>$modulelink)
		{
			$template->assign_block_vars('module_loop',array(
																'MODULE_NAME' => $modulename,
																'MODULE_LINK' => 'modules/' . $modulelink,
															));
		}
	}
    
    $template->set_filenames(array(
                                   'body' => 'home.html'
                                   ));
    $template->display('body');
    page_footer();
?>