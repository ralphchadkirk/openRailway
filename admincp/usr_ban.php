<?php
	// Deal with usr_ban form
	if(isset($_GET['action']))
	{
		if(($_GET['action'] == 'ban') && isset($_POST['user']))
		{
			Authentication::suspendUser($_POST['user']);
			$successAlert = 1;
		
		}

		// Deal with unban form
		if(($_GET['action'] == 'unban') && isset($_POST['unbanID']))
		{
			Authentication::reinstateUser($_POST['unbanID']);
			$successAlert = 1;
		
		}
	}
	
	$sqlSuspend = "SELECT * FROM `users` WHERE `suspended` = '0'";
	$resultSuspend = openRailwayCore::dbQuery($sqlSuspend);
	
	$sqlReinstate = "SELECT * FROM `users` WHERE `suspended` = '1'";
	$resultReinstate = openRailwayCore::dbQuery($sqlReinstate);
	
	$main = new Template;
	$main->set_custom_template("includes/",'default');
	$main->assign_var('ROOT',ROOT);
	
	while($accountSuspend = mysql_fetch_assoc($resultSuspend))
	{
		$main->assign_block_vars('user_loop',array(
													'UID' => $accountSuspend['user_id'],
													'NAME' => $accountSuspend['username'],
													'SID' => $accountSuspend['staff_id'],
												   ));
	}
	
	while($accountReinstate = mysql_fetch_assoc($resultReinstate))
	{
		$main->assign_block_vars('user_sus_loop',array(
												   'UID' => $accountReinstate['user_id'],
												   'NAME' => $accountReinstate['username'],
												   'SID' => $accountReinstate['staff_id'],
												   ));
	}
	
	$main->set_filenames(array(
							   'main' => "usr_ban.html"
							   ));
	$main->display('main');
	
?>