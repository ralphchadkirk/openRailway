<?php
	include("config.php");
	session_start();
	openRailwayCore::initialisation();
	openRailwayCore::dbConnect();
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
		// Display profile
		Authentication::blockPageToVisitors();
		openRailwayCore::pageHeader("");
		openRailwayCore::pageFooter();
	}
	elseif(isset($_GET['mode']))
	{
		// Modes
		switch($_GET['mode']):
			case "account":
				Authentication::blockPageToVisitors();
				// Account actions
				if(isset($_GET['action']))
				{
					switch($_GET['action']):
						case "deactivate":
							// Deactivates account
							if(isset($_SESSION['user_id']))
							{
								Authentication::deactivateUser($_SESSION['user_id']);
							}
						break;
						case "update":
							// Update user details - AJAX implementation
							if(isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['sname']) && isset($_POST['address']) && isset($_POST['dob']) && isset($_POST['mphone']) && isset($_POST['wphone']) && isset($_POST['hphone']) && isset($_POST['email']))
							{
								openRailwayCore::dbQuery("UPDATE `staff_master` SET `first_name` = '" . $_POST['fname'] . "', `middle_name` = '" . $_POST['mname'] . "', `surname` = '" . $_POST['sname'] . "', `date_of_birth` = '" . $_POST['dob'] . "', `address` = '" . $_POST['address'] . "', `email` = '" . $_POST['email'] . "', `home_phone` = '" . $_POST['hphone'] . "', `mobile_phone` = '" . $_POST['mphone'] . "', `work_phone` = '" . $_POST['wphone'] . "' WHERE `staff_id` = '" . $_SESSION['staff_id'] . "'");
								openRailwayCore::logEvent(time(),openRailwayCore::createInteractionIdentifier(),"user::case::update",$_SESSION['user_id'],5,0,"User profile updated");

							} else
							{
								header("Location: " . ROOT . "user.php?mode=account");
							}
						break;
						case "changepassword":
							if(isset($_POST['oldpassword']) && isset($_POST['newpassword']) && isset($_POST['confirmpassword']))
							{
								// Change password code
							}
						break;
						default:
							header("Location: " . ROOT . "user.php?mode=account");
						break;
					endswitch;
				}
				$result = openRailwayCore::dbQuery("SELECT * FROM `staff_master` WHERE `staff_id` = '" . $_SESSION['staff_id'] . "'");
				$staff = mysql_fetch_assoc($result);
				openRailwayCore::pageHeader("Account settings");
				$template = new Template;
				$template->set_custom_template("theme/" . STYLE,'default');
				$template->assign_var('ROOT',ROOT);
		
				// Profile
				$template->assign_var('FNAME',$staff['first_name']);
				$template->assign_var('MNAME',$staff['middle_name']);
				$template->assign_var('SNAME',$staff['surname']);
				$template->assign_var('ADDRESS',$staff['address']);
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
			// Access Levels
			case "access":
				Authentication::blockPageToVisitors();
				openRailwayCore::pageHeader("Access Levels");
				$template = new Template;
				$template->set_custom_template("theme/" . STYLE,'default');
				$template->assign_var('ROOT',ROOT);
				$template->set_filenames(array(
											   'body' => 'access-levels.html'
											   ));
				$template->display('body');
				openRailwayCore::pageFooter();
			break;
			// Account activation
			case "activate":
				if(isset($_GET['action']))
				{
					switch($_GET['action']):
						case "activate":
							Authentication::activateUser($_POST['actkey']);
						break;
					endswitch;
				}
				openRailwayCore::pageHeader("Activate your account");
				$template = new Template;
				$template->set_custom_template("theme/" . STYLE,'default');
				$template->assign_var('ROOT',ROOT);
				if(isset($_GET['l']) && $_GET['l'] == 'fail')
				{
					$template->assign_block_vars('if_activation_unsuccessful',array());
				}
				$template->set_filenames(array(
											   'body' => 'activate.html'
											   ));
				$template->display('body');
				openRailwayCore::pageFooter();
			break;
			// Authentication
			case "auth":
				if(isset($_GET['action']))
				{
					switch($_GET['action']):
						case "login":
							if(isset($_POST['username']) && isset($_POST['password']))
							{
								Authentication::logUserIn($_POST['username'],$_POST['password']);
							}
							else
							{
								header(ROOT . "user.php?mode=auth&action=login");
							}
							Authentication::blockPageToVisitors();
						break;
						case "logout":
							Authentication::logUserOut();
						break;
						case "activate":
						break;
						case "deactivate":
						break;
					endswitch;
				}
			break;
			default:
				Authentication::blockPageToVisitors();
				// If invalid mode, redirect to account
				header("Location: " . ROOT . "user.php?mode=account");
			break;
		endswitch;
	}
	else
	{
		// If no mode then redirect to account
		header("Location: " . ROOT . "user.php?mode=account");
	}
?>