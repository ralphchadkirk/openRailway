<?php
    include("config.php");
    include("lib/dbwrapper.php");
    include("lib/errorhandler.php");
    include("lib/template.php");
    
    page_header("About openRailway");
    $template = new Template();
    $template->set_custom_template('theme','default');
    $template->set_filenames(array(
                                   'body' => 'openrailway.html'
                                   ));
    $template->display('body');
    page_footer();
    ?>