<?php
	
	if(isset($_GET['action']) && $_GET['action'] == 'deleteall')
	{
		$sql = "DELETE FROM `log` WHERE `security_relevant` = '1'";
		$result = openRailwayCore::dbQuery($sql);
		openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),$_SESSION['user_id'],1,1,'User deleted all security log entries');
		header("Location: " . ROOT . "admincp/index.php?module=log_err");
	}
	
	$main = new Template;
	$main->set_custom_template("includes/",'default');
	$main->assign_var('ROOT',ROOT);
	
	if(isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$main->assign_block_vars('if_linkback','');
	} elseif(isset($_GET['int']))
	{
		$sql = "SELECT * FROM `log` WHERE `interaction_identifier` = '" . $_GET['int'] . "' ORDER BY `event_id` DESC";
		$result = openRailwayCore::dbQuery($sql);
		$main->assign_block_vars('if_linkback','');
		$main->assign_block_vars('if_table_display','');
		while($event = mysql_fetch_assoc($result))
		{
			if(isset($event))
			{
				$main->assign_var('SUBTITLE',"Viewing interaction " . $_GET['int']);
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
	} else
	{
		$sql = "SELECT * FROM `log` WHERE `security_relevant` = '1' ORDER BY `event_id` DESC";
		$result = openRailwayCore::dbQuery($sql);
		$main->assign_block_vars('if_table_display','');
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
			} else
			{
				$main->assign_block_vars('no_results','');
			}
		}
	}
	
	$main->set_filenames(array(
							   'main' => "log_err.html"
							   ));
	$main->display('main');
?>