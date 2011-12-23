<?php
    include("config.php");
    include("lib/dbwrapper.php");
    include("lib/errorhandler.php");
    include("lib/template.php");
    db_connect();
    
    // Check to see if a system message is set
//    $sql = "SELECT `value` FROM `" . CONFIG_TABLE . "` WHERE `key` = 'sysmess'";
 //   $sys = mysql_fetch_assoc(db_query($sql));
    
    page_header("Home");
    $template = new Template();
    $template->set_custom_template('theme','default');
    // If a system message is set, we'll enable the sysmess block
//    if(1 == 1)
 //   {
   //     $template->assign_block_vars('system_message',array(
     //                                                       'TEXT' => $sys['value'],
 //                                                           ));
//    }
    $template->set_filenames(array(
                                   'body' => 'home.html'
                                   ));
    $template->display('body');
    page_footer();
?>