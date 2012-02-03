<?php
    define(ROOT,'http://localhost/~RChadkirk/openRailway/');
    define(FROOT,'/Users/RChadkirk/Sites/openRailway/');
    $railway_name = "Railway Name";

// ---------------------------
// DO NOT EDIT BELOW THIS LINE
// ---------------------------
	
	function openRailway_init()
	{
		include(FROOT . "lib/dbwrapper.php");
		include(FROOT . "lib/errorhandler.php");
		include(FROOT . "lib/functions.php");
		include(FROOT . "lib/template.php");
	}

	error_reporting(E_ALL);
	
	// Make sure that nobody who isn't logged in can see anything
/*	if(!isset($_SESSION['session_id']))
	{
		include(FROOT . "lib/template.php");
		page_header("Access not authorised");
		$template = new Template();
		$template->set_custom_template('theme','default');
   		$template->set_filenames(array(
                                		'login' => 'login.html'
                                   		));
    	$template->display('login');
		page_footer();
		// End output
		die();
	} */
?>