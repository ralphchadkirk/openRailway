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
			case "profile":
				openRailwayCore::pageHeader("Your profile");
				openRailwayCore::pageFooter();
			break;
			case "messages":
				if(isset($_GET['folder']))
				{
					$folder = $_GET['folder'];
				}
				else
				{
					$folder = "inbox";
				}
				$active_class = "class='active'";
				$icon_white = "icon-white";
				switch($folder):
					case "inbox":
						$title = "Inbox";
						$name = "inbox";
						$sql = "";
					break;
					case "outbox":
						$title = "Outbox";
						$name = "outbox";
						$sql = "";
					break;
					case "sent":
						$title = "Sent messages";
						$name = "sent";
						$sql = "";
					break;
					default:
						trigger_error("An invalid folder has been provided",E_USER_WARNING);
					break;
				endswitch;
				openRailwayCore::pageHeader($title,"Messages");
				$template = new Template();
				$template->set_custom_template("theme/" . STYLE,'default');
				$template->assign_var('ROOT',ROOT);
				$template->assign_var('TITLE',$title);
				if(isset($name))
				{
					if($name == "inbox")
					{
					   $template->assign_var('INBOX_ACTIVE',$active_class);
					   $template->assign_var('INBOX_WHITE',$icon_white);
					}
					elseif($name == "outbox")
					{
						$template->assign_var('OUTBOX_ACTIVE',$active_class);
						$template->assign_var('OUTBOX_WHITE',$icon_white);
					}
					elseif($name == "sent")
					{
						$template->assign_var('SENT_ACTIVE',$active_class);
						$template->assign_var('SENT_WHITE',$icon_white);
					}
				}
				$template->set_filenames(array(
												'body' => 'messages.html'
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
		trigger_error("Correct URL paramaters do not exist",E_USER_ERROR);
	}
?>