<?php
    include("config.php");
    include("lib/dbwrapper.php");
    include("lib/errorhandler.php");
    include("lib/template.php");
    db_connect();
    
    // Check to see if a system message is set
    $sql = "SELECT * `title` `body` FROM `" . CONFIG_TABLE . "` WHERE `key` = 'sysmess'";
    db_query($sql);
    
    page_header("Home");
    $template = new Template();
    $template->set_custom_template('theme','default');
    $template->set_filenames(array(
                                   'body' => 'home.html'
                                   ));
    $template->display('body');
    page_footer();
?>