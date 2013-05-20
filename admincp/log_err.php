<?php
	Authentication::accessLevelController(8,'>');
	$sql = "SELECT * FROM `log` WHERE `security_relevant` = '1' ORDER BY `event_id` DESC";
	$result = openRailwayCore::dbQuery($sql);
	
	$main = new Template;
	$main->set_custom_template("includes/",'default');
	$main->assign_var('ROOT',ROOT);
	
	while($event = mysql_fetch_assoc($result))
	{
		if(isset($event))
		{
			$main->assign_block_vars('log_err',array(
													 'ID' => $event['event_id'],
													 'TIME' => date("d-M-Y H:i:s",$event['event_timestamp']),
													 'SEV' => $event['event_severity'],
													 'INTID' => $event['interaction_identifier'],
													 'IP' => $event['source_ip'],
													 'SUA' => $event['source_user_agent'],
													 'DESC' => $event['description'],
													 ));
		}
	}
	$main->set_filenames(array(
							   'main' => "log_err.html"
							   ));
	$main->display('main');
?>