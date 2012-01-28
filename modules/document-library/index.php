<?php
	include("../../config.php");
	openRailway_init();
	getModuleConfig("document-library");
	db_connect();
	
	if($_GET['fid'] !== null)
	{
		$sql = "SELECT * FROM `doclib_folders` WHERE `parent_id` = '" . $_GET['fid'] . "'";
		$sql_result = mysql_query($sql);
		
		$title_sql = "SELECT `folder_name` FROM `doclib_folders` WHERE `folder_id` = '" . $_GET['fid'] . "'";
		$title_sql_result = mysql_query($title_sql);
		$title = mysql_fetch_assoc($title_sql_result);
		page_header($title['folder_name'],$module['name']);
		$template = new Template();
    	$template->set_custom_template('html','default');
    	$template->assign_var("FOLDER_NAME",$title['folder_name']);
		
		if(mysql_num_rows($sql_result) == 0)
		{
			$template->assign_block_vars('switch_empty_recordset',array(
																		));
		}
    	while($folder = mysql_fetch_assoc($sql_result))
    	{
    		$template->assign_block_vars('folderlist_loop',array(
    															'NAME' => $folder['folder_name'],
    															'DESC' => $folder['folder_desc'],
    															'ID' => $folder['folder_id'],
    														));
    	}
		$template->set_filenames(array(
										'folder-list' => 'folder-list.html'
     	                              ));
     	$template->assign_block_vars('switch_current_folder',array(
     																'NAME' => $title['folder_name'],
     																));
     	$template->display('folder-list');
    
		page_footer();
		
		doclibBreadcrumbArray($_GET['fid']);
		print_r($name);
	} else
	{
		$sql = "SELECT * FROM `doclib_folders` WHERE `parent_id` IS NULL";
		$sql_result = mysql_query($sql);
    	
    	page_header("Library Home",$module['name']);
    	$template = new Template();
    	$template->set_custom_template('html','default');
    	
    	if(mysql_num_rows($sql_result) == 0)
		{
			$template->assign_block_vars('switch_empty_recordset',array(
																		));
		}
		
    	$template->assign_var("FOLDER_NAME","Library Home");
    	while($folder = mysql_fetch_assoc($sql_result))
    	{
    		$template->assign_block_vars('folderlist_loop',array(
    															'NAME' => $folder['folder_name'],
    															'DESC' => $folder['folder_desc'],
    															'ID' => $folder['folder_id'],
    														));
    	}
		$template->set_filenames(array(
										'folder-list' => 'folder-list.html'
     	                              ));
     	$template->display('folder-list');
    
		page_footer();
	}
	
?>