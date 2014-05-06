<?php
	Authentication::accessLevelController(8,'>');
	// Deal with logout form
	if(isset($_GET['action']) && $_GET['action'] == "force" && isset($_POST['uid']))
	{
		Authentication::logUserOut($_POST['uid'],openRailwayCore::createInteractionIdentifier(),1);
	}
	
	$sql = "SELECT * FROM `sessions`";
	$result = openRailwayCore::dbQuery($sql);
	
	$main = new Template;
	$main->set_custom_template("includes/",'default');
	$main->assign_var('ROOT',ROOT);
	
	while($sessions = mysql_fetch_assoc($result))
	{
		$ipGeoLoc = array();
		$ipGeoLoc = Authentication::checkIPLocation($sessions['user_ip']);
		if( $ipGeoLoc['town'] == '')
		{
			$geoLoc = null;
		} else
		{
			$geoLoc = $ipGeoLoc['town'] . ", " . $ipGeoLoc['state'] . ", " . $ipGeoLoc['country'];
		}
		$main->assign_block_vars('usr_sess',array(
													'SESSID' => $sessions['session_id'],
													'LOGIN' => date("d-M-Y H:i:s",$sessions['log_in_time']),
													'LASTACTIVE' => date("d-M-Y H:i:s",$sessions['last_active_time']),
													'UID' => $sessions['user_id'],
													'SID' => $sessions['staff_id'],
												    'IP' => $sessions['user_ip'],
													'GEOLOC' => $geoLoc,
													'UA' => $sessions['user_agent'],
													'SAL' => $sessions['session_access_level'],
													));
	}

	$main->set_filenames(array(
							   'main' => "usr_sess.html"
							   ));
	$main->display('main');
	?>