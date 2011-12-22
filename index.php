<?php
    include("config.php");
    include("lib/dbwrapper.php");
    include("lib/errorhandler.php");
    include("lib/template.php");
    
    $template = new Template();
    $template->set_custom_template('theme','default');
    $template->assign_var('TITLE','Home');
    $template->assign_var('ROOT',ROOT . "theme/");
    $template->set_filenames(array(
                                   'head' => 'header.html',
                                   'foot' => 'footer.html',
                                   ));
    $template->display('head');
    $template->display('foot');
?>