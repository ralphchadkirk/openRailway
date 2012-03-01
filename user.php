<?php
	include("config.php");
	session_start();
	openRailwayCore::initialisation();
	openRailwayCore::dbConnect();
	Authentication::blockPageToVisitors();
	if((isset($_GET['mode'])) && (isset($_GET['uid'])))
	{
		trigger_error("UID and MODE are set",E_USER_ERROR);
	}
	elseif((isset($_GET['uid'])))
	{
		if(!is_numeric($_GET['uid']))
		{
			trigger_error("UID parameter should be integer",E_USER_ERROR);
		}
		openRailwayCore::pageHeader("");
		openRailwayCore::pageFooter();
	}
	elseif(isset($_GET['mode']))
	{
		switch($_GET['mode']):
			case "account":
				if(isset($_GET['action']))
				{
					switch($_GET['action']):
						case "deactivate":
							if(isset($_SESSION['user_id']))
							{
								Authentication::deactivateUser($_SESSION['user_id']);
							}
						break;
					endswitch;
				}
				$result = openRailwayCore::dbQuery("SELECT * FROM `staff_master` WHERE `staff_id` = '" . $_SESSION['staff_id'] . "'");
				$staff = mysql_fetch_assoc($result);
				openRailwayCore::pageHeader("Your account");
				$template = new Template;
				$template->set_custom_template("theme/" . STYLE,'default');
		
				// Profile
				$template->assign_var('FNAME',$staff['first_name']);
				$template->assign_var('MNAME',$staff['middle_name']);
				$template->assign_var('SNAME',$staff['surname']);
				$template->assign_var('ADDRESS',nl2br($staff['address']));
				$template->assign_var('EMAIL',$staff['email']);
				$template->assign_var('HPHONE',$staff['home_phone']);
				$template->assign_var('WPHONE',$staff['work_phone']);
				$template->assign_var('MPHONE',$staff['mobile_phone']);
				$template->assign_var('DOB',$staff['date_of_birth']);
		
				$template->set_filenames(array(
												'body' => 'account.html'
												));
				$template->display('body');
				openRailwayCore::pageFooter();
			break;
			default:
				trigger_error("An invalid MODE parameter has been provided",E_USER_ERROR);
		endswitch;
	}
	else
	{
		trigger_error("Correct URL parameters do not exist",E_USER_ERROR);
	}
?>