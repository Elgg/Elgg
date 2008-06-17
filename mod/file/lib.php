<?php

require_once($CFG->dirroot.'lib/filelib.php');

function file_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;
    global $metatags;
    
    require_once (dirname(__FILE__)."/lib/file_config.php");
    $page_owner = $profile_id;
    
    if (isloggedin()) {
        if (defined("context") && context == "files" && $page_owner == $_SESSION['userid']) {
            $PAGE->menu[] = array( 'name' => 'files',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/files/\" class=\"selected\" >" .__gettext("Your Files").'</a></li>');
        } else {
            $PAGE->menu[] = array( 'name' => 'files',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/files/\" >" .__gettext("Your Files").'</a></li>');
        }
    }

    if (defined("context") && context == "files") {
        
        $files_username = user_info('username', $page_owner);

        if ($page_owner != -1) {
            if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
                $PAGE->menu_sub[] = array( 'name' => 'file:rss', 
			'html' => '<a href="' . $CFG->wwwroot . $_SESSION['username'] . '/files/rss/"><img src="' . $CFG->wwwroot . 'mod/template/icons/rss.png" border="0" alt="rss" /></a>');
            }
        }

        if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
            $PAGE->menu_sub[] = array( 'name' => 'file:add',
                                       'html' => a_href( "#addFile",
                                                          __gettext("Add a file or a folder")));
    }
	}
  // Adding the file's selector wizard
  $options = array('options'=> 'width=600,height=300,left=20,top=20,scrollbars=yes,resizable=yes',
                   'name'=> 'mediapopup',
                   // 'url' => $CFG->wwwroot."mod/file/file_include_wizard.php?owner=".$_SESSION['userid']);
                   'url' => $CFG->wwwroot."mod/file/file_include_wizard.php?owner=".page_owner());
  add_content_tool_button("mediapopup",__gettext("Add File"), "image.png", "f", $options);

}

function file_init() {
    global $CFG;
    global $function;
    global $metatags;

    // Styles for file icons
        $metatags .= "<style type=\"text/css\">";
        $metatags .= str_replace("{{url}}", $CFG->wwwroot, file_get_contents(dirname(__FILE__). "/file-icons.css"));
        $metatags .= "</style>";
        
    // Functions to perform upon initialisation
        $function['files:init'][] = $CFG->dirroot . "mod/file/lib/files_init.php";
        $function['files:init'][] = $CFG->dirroot . "mod/file/lib/metadata_defaults.php";
        $function['files:init'][] = $CFG->dirroot . "mod/file/lib/inline_mimetypes.php";
        $function['init'][] = $CFG->dirroot . "mod/file/default_templates.php";
    
    // Mime-type init
        $function['files:metadata:init'][] = $CFG->dirroot . "mod/file/lib/inline_mimetypes.php";
        
    // Actions to perform
        $function['files:init'][] = $CFG->dirroot . "mod/file/lib/files_actions.php";

    // Init for search
        $function['search:init'][] = $CFG->dirroot . "mod/file/lib/files_init.php";
        $function['search:all:tagtypes'][] = $CFG->dirroot . "mod/file/lib/function_search_all_tagtypes.php";
        
    // Function to search through weblog posts
        $function['search:display_results'][] = $CFG->dirroot . "mod/file/lib/function_search.php";
        $function['search:display_results:rss'][] = $CFG->dirroot . "mod/file/lib/function_search_rss.php";
        
    // Determines whether or not a file should be displayed in the browser
        $function['files:mimetype:inline'][] = $CFG->dirroot . "mod/file/lib/files_mimetype_inline.php";
        
    // View files
        $function['files:view'][] = $CFG->dirroot . "mod/file/lib/files_view.php";

    // View the contents of a specific folder
        $function['files:folder:view'][] = $CFG->dirroot . "mod/file/lib/folder_view.php";
        
    // Edit the contents of a specific folder
        $function['files:folder:edit'][] = $CFG->dirroot . "mod/file/lib/edit_folder.php";

    // Add files through the wizard
        $function['files:wizard:add:file'][] = $CFG->dirroot . "mod/file/lib/add_file.php";
        
    // Edit the metadata for a specific file
        $function['files:edit'][] = $CFG->dirroot . "mod/file/lib/edit_file.php";
        $function['folder:select'][] = $CFG->dirroot . "mod/file/lib/select_folder.php";
    
    // Edit metadata
        $function['metadata:edit'][] = $CFG->dirroot . "mod/file/lib/metadata_edit.php";
        
    // Turn file ID into a link
        $function['files:links:make'][] = $CFG->dirroot . "mod/file/lib/files_links_make.php";
        
    // Allow users to embed files in weblog posts
        $function['weblogs:text:process'][] = $CFG->dirroot . "mod/file/lib/weblogs_text_process.php";

        $function['weblogs:posts:add:fields'][] = $CFG->dirroot . "mod/file/lib/weblogs_posts_add_fields.php";
        $function['weblogs:posts:edit:fields'][] = $CFG->dirroot . "mod/file/lib/weblogs_posts_add_fields.php";
                    
    // Log on bar down the right hand side
        $function['display:sidebar'][] = $CFG->dirroot . "mod/file/lib/files_user_info_menu.php";
        
    // Template preview
        $function['templates:preview'][] = $CFG->dirroot . "mod/file/lib/templates_preview.php";

    // Establish permissions
        $function['permissions:check'][] = $CFG->dirroot . "mod/file/lib/permissions_check.php";

    // Actions to perform when an access group is deleted
        $function['groups:delete'][] = $CFG->dirroot . "mod/file/lib/groups_delete.php";

    // Publish static RSS file of files
        $function['files:rss:getitems'][] = $CFG->dirroot . "mod/file/lib/function_rss_getitems.php";
        $function['files:rss:publish'][] = $CFG->dirroot . "mod/file/lib/function_rss_publish.php";
    
    // Has the $CFG->files->default_handler been set? If not, set it to local
        if (empty($CFG->files->default_handler)) {
            $CFG->files->default_handler = "elgg";
        }
        if (empty($CFG->folders->default_handler)) {
            $CFG->folders->default_handler = "elgg";
        }
        $CFG->folders->handler["elgg"]['menuitem'] = __gettext("Default file folder");
        $CFG->folders->handler["elgg"]['view'] = "file_folder_view";
        $CFG->folders->handler["elgg"]['preview'] = "file_folder_preview";
        
        $CFG->widgets->list[] = array(
                                        'name' => __gettext("Files widget"),
                                        'description' => __gettext("Displays images of some of your files."),
                                        'type' => "file::files"
                                );
        
   // Delete users
        listen_for_event("user","delete","file_user_delete");
                
	// Register a display object function
	display_set_display_function('file', 'file_displayobject');
}

function file_permissions_check($objecttype, $owner){
    $run_result = null;

	if ($objecttype == "files" || $objecttype == "files:edit") {
	    if (logged_on && $owner == $_SESSION['userid']) {
	        $run_result = true;
	    }
	}
	return $run_result;
}
	
function file_displayobject($object_id,$object_type)
{
	global $page_owner, $CFG;
        $owner_username = user_info('username', $page_owner);

	$return = "";

	if ($object_type=="file::file")
	{
		if ($file = get_record_select('files', "ident=$object_id"))
		{
			if (run("users:access_level_check",$file->access) == true || $file->owner == $_SESSION['userid']) 
			{
	
				$username = $owner_username;
				$ident = (int) $file->ident;
				$folder->ident = $file->folder;
				$title = get_access_description($file->access);
				$title .= stripslashes($file->title);
				$description = nl2br(stripslashes($file->description));
				$filetitle = urlencode($title);
				$originalname = stripslashes($file->originalname);
				$filemenu = round(($file->size / 1048576),4) . "MB ";
				$icon = $CFG->wwwroot . "_icon/file/" . $file->ident;
				$filepath = $CFG->wwwroot . "$username/files/$folder->ident/$ident/" . urlencode($originalname);
				
				$mimetype = mimeinfo('type',$file->originalname);
	
				if ($mimetype == "audio/mpeg" || $mimetype == "audio/mp3") {
					$filemenu .= " <object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"
					codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\"
					width=\"17\" height=\"17\" >
					<param name=\"allowScriptAccess\" value=\"sameDomain\" />
					<param name=\"movie\" value=\"" . $CFG->wwwroot . "mod/file/mp3player/musicplayer.swf?song_url=$filepath&amp;song_title=$filetitle\" />
					<param name=\"quality\" value=\"high\" />
					<embed src=\"" . $CFG->wwwroot . "mod/file/mp3player/musicplayer.swf?song_url=$filepath&amp;song_title=$filetitle\"
					quality=\"high\" bgcolor=\"#E6E6E6\" name=\"xspf_player\" allowscriptaccess=\"sameDomain\"
					type=\"application/x-shockwave-flash\"
					pluginspage=\"http://www.macromedia.com/go/getflashplayer\"
					align=\"center\" height=\"17\" width=\"17\" />
					</object>";
				}
	
	
				$keywords = display_output_field(array("","keywords","file","file",$ident,$file->owner));
				if ($keywords) {
					$keywords = __gettext("Keywords: ") . $keywords;
				}
	
				$return = <<< END
					<table>
						<tr>
							<td><img src="$icon" alt="$originalname" /></td>
							<td>
								<p><b>$title</b></p>
								<p>$description</p>
								<p>$originalname</p>
							</td>
						</tr>
					</table>
END;
	
			}
		}
	}
	
	
	return $return;

}

    function file_user_delete($object_type, $event, $object) {
        global $CFG;
        if (!empty($object->ident) && $object_type == "user" && $event == "delete") {
            if ($files = get_records_sql("select * from {$CFG->prefix}files where owner = {$object->ident} or files_owner = {$object->ident}")) {
                foreach($files as $file) {
                    $file = plugin_hook("file","delete",$file);
                    if (!empty($file)) {
                        @unlink(stripslashes($CFG->dataroot.$file->location));
                        delete_records('files','ident',$file->ident);
                        delete_records('tags','tagtype','file','ref',$file->ident);
                    }
                }
            }
            @unlink($CFG->dataroot."files/".substr($object->username,0,1)."/".$object->username);
            if ($folders = get_records_sql("select * from {$CFG->prefix}file_folders where owner = {$object->ident} or files_owner = {$object->ident}")) {
                foreach($folders as $folder) {
                    $folder = plugin_hook("folder","delete",$folder);
                    if (!empty($folder)) {
                        set_field('file_folders','parent',-1,'parent',$folder->ident);
                        set_field('files','folder',-1,'folder',$folder->ident);
                        delete_records('file_folders','ident',$folder->ident);
                        delete_records('tags','tagtype','folder','ref',$folder->ident);
                    }
                }
            }
        }

        return $object;
    }

function file_widget_display($widget) {
    global $CFG;
    $latest_files = clean_param(widget_get_data("latest_files",$widget->ident),PARAM_INT);
    $html = "<p>" . __gettext("No files found.") . "</p>";
    if ($widget->type == "file::files") {
        $where1 = run("users:access_level_sql_where",$_SESSION['userid']);
        if ($files = get_records_sql("select * from {$CFG->prefix}files where files_owner = {$widget->owner} and (".$where1.") order by time_uploaded desc limit {$latest_files}")) {
            $html = "";
            foreach ($files as $file) {
                $keywords = display_output_field(array("","keywords","file","file",$file->ident,$file->owner));
                    if ($keywords) {
                        $keywords = __gettext("Keywords: ") . $keywords;
                    }
                $html .= templates_draw(array(
                                                  'context' => 'file',
                                                  'username' => user_info("username",$file->files_owner),
                                                  'title' => $file->title,
                                                  'ident' => $file->ident,
                                                  'folder' => $file->folder,
                                                  'description' => $file->description,
                                                  'originalname' => $file->originalname,
                                                  'url' => $CFG->wwwroot . user_info("username",$file->files_owner) . "/files/$file->folder/$file->ident/" . urlencode($file->originalname),
                                                  'menu' => "",
                                                  'icon' => $CFG->wwwroot . "_icon/file/" . $file->ident,
                                                  'keywords' => $keywords
                                                  )
                                            );
            }
        }
    }
    return array("title" => __gettext("Latest files") , "content" => $html);
}

function file_widget_edit($widget) {
    $latest_files = clean_param(widget_get_data("latest_files",$widget->ident),PARAM_INT);
    $body = "";
    $body = "<h2>" . __gettext("Files widget") . "</h2>";
    $body .= "<p>" . __gettext("This widget displays the last couple of files from this account. Simply select the number of latest files below:") . "</p>";
    $body .= "<p><input type=\"text\" name=\"widget_data[latest_files]\" value=\"" . $latest_files . "\" /></p>";
    return $body;
}



    function file_folder_view($folder) {
        
        global $CFG;
        /*
         *    View a specific folder
         *    (Access rights are presumed)
         */
        
        // Find out who's the owner
            
        global $page_owner;
        $owner_username = user_info('username', $page_owner);
        
        // If we're not in the parent folder, provide a link to return to the parent
        
        /*
        if ($folder->ident != -1) {
            $folder->name = stripslashes($folder->name);
        }
        */
                
        $body = "<h2>" . $folder->name . "</h2>";
        
        // Firstly, get a list of folders
        // Display folders we actually have access to
        if ($folder->idents = get_records_select('file_folders',"parent = $folder->ident AND (". run("users:access_level_sql_where") . ") and files_owner = $page_owner")) {
            $subFolders = __gettext("Subfolders"); // gettext variable
            $body .= <<< END
                
                            <h3>
                                $subFolders
                            </h3>
        
END;
            
            foreach($folder->idents as $folder->ident_details) {
                if (run("users:access_level_check",$folder->ident_details->access) == true) {
                    $username = $owner_username;
                    $ident = (int) $folder->ident_details->ident;
                    $name = get_access_description($folder->ident_details->access);
                    $name .= stripslashes($folder->ident_details->name);
                    $folder->identmenu = file_folder_edit_links($folder->ident_details);
                    $keywords = display_output_field(array("","keywords","folder","folder",$ident,$folder->ident_details->owner));
                    if ($keywords) {
                        $keywords = __gettext("Keywords: ") . $keywords;
                    }
                    $body .= templates_draw(array(
                                                  'context' => 'folder',
                                                  'username' => $username,
                                                  'url' => $CFG->wwwroot . "$username/files/$ident",
                                                  'ident' => $ident,
                                                  'name' => $name,
                                                  'menu' => $folder->identmenu,
                                                  'icon' => $CFG->wwwroot . "mod/file/folder.png",
                                                  'keywords' => $keywords
                                                  )
                                            );
			

                }
                
            }
        }
            
        // Then get a list of files
        // View files we actually have access to
        if ($files = get_records_select('files',"folder = ? AND files_owner = ?",array($folder->ident,$page_owner))) {
            foreach($files as $file) {
                
                if (run("users:access_level_check",$file->access) == true || $file->owner == $_SESSION['userid']) {
                    $username = $owner_username;
                    $ident = (int) $file->ident;
                    $folder->ident = $file->folder;
                    $title = get_access_description($file->access);
                    $title .= stripslashes($file->title);
                    $description = nl2br(stripslashes($file->description));
                    $filetitle = urlencode($title);
                    $originalname = stripslashes($file->originalname);
                    $filemenu = round(($file->size / 1048576),4) . "MB ";
                    $icon = $CFG->wwwroot . "_icon/file/" . $file->ident;
                    $filepath = $CFG->wwwroot . "$username/files/$folder->ident/$ident/" . urlencode($originalname);
                    
                    $mimetype = mimeinfo('type',$file->originalname);
                    if ($mimetype == "audio/mpeg" || $mimetype == "audio/mp3") {
                        $filemenu .= " <object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"
        codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\"
        width=\"17\" height=\"17\" >
            <param name=\"allowScriptAccess\" value=\"sameDomain\" />
            <param name=\"movie\" value=\"" . $CFG->wwwroot . "mod/file/mp3player/musicplayer.swf?song_url=$filepath&amp;song_title=$filetitle\" />
            <param name=\"quality\" value=\"high\" />
            <embed src=\"" . $CFG->wwwroot . "mod/file/mp3player/musicplayer.swf?song_url=$filepath&amp;song_title=$filetitle\"
            quality=\"high\" bgcolor=\"#E6E6E6\" name=\"xspf_player\" allowscriptaccess=\"sameDomain\"
            type=\"application/x-shockwave-flash\"
            pluginspage=\"http://www.macromedia.com/go/getflashplayer\"
            align=\"center\" height=\"17\" width=\"17\" />
        </object>";
                    }
                    $filemenu = file_edit_links($file);
                    $keywords = display_output_field(array("","keywords","file","file",$ident,$file->owner));
                    if ($keywords) {
                        $keywords = __gettext("Keywords: ") . $keywords;
                    }
                    $body .= templates_draw(array(
                                                  'context' => 'file',
                                                  'username' => $username,
                                                  'title' => $title,
                                                  'ident' => $ident,
                                                  'folder' => $folder->ident,
                                                  'description' => $description,
                                                  'originalname' => $originalname,
                                                  'url' => $filepath,
                                                  'menu' => $filemenu,
                                                  'icon' => $icon,
                                                  'keywords' => $keywords
                                                  )
                                            );

		     $body .= display_run_displayobjectannotations($file, "file::file");
                }
                
            }
            
        }
        
        // Deliver an apologetic message if there aren't any files or folders
        
        if (empty($files) && empty($folder->idents)) {
            
            $body .= "<p>" . __gettext("This folder is currently empty.") . "</p>";
            
        }

        return $body;
        
        
    }
    
    function file_edit_links($file) {
        
        global $page_owner, $CFG;

        $filemenu = "";
        if (permissions_check("files:edit", $file->owner)  || permissions_check("files:edit", $file->files_owner)) {
                        $delete = __gettext("Delete"); // gettext variable
                        $edit = __gettext("Edit"); // gettext variable
                        $filemenu .= <<< END
            [<a href="{$CFG->wwwroot}mod/file/edit_file.php?edit_file_id={$file->ident}&amp;owner=$page_owner">$edit</a>]
            [<a href="{$CFG->wwwroot}mod/file/action_redirection.php?action=delete_file&amp;delete_file_id={$file->ident}">$delete</a>]
END;
        }
        return $filemenu;
        
    }

    function file_folder_edit_links($folder) {

        global $page_owner, $CFG;
        $foldermenu = "";
                
        if (permissions_check("files:edit", $folder->owner)  || permissions_check("files:edit", $folder->files_owner)) {
            $delete = __gettext("Delete"); // gettext variable
            $edit = __gettext("Edit"); // gettext variable
            $foldermenu = <<< END
            [<a href="{$CFG->wwwroot}mod/file/edit_folder.php?edit_folder_id={$folder->ident}&amp;owner=$page_owner&amp;return_type=parent">$edit</a>]
            [<a href="{$CFG->wwwroot}mod/file/action_redirection.php?action=delete_folder&amp;delete_folder_id={$folder->ident}">$delete</a>]
END;
        }
        return $foldermenu;
        
    }
        
    function file_folder_preview($folder) {
        
    }
    
    function file_folder_type_switcher($folder, $label) {
        
        global $CFG;
        $html = "";
        if (is_array($CFG->folders->handler)) {
            foreach($CFG->folders->handler as $key => $handler) {
                $html .= "<option value=\"" . $key . "\"";
                if (!empty($folder->handler) && $key == $folder->handler) {
                    $html .= " selected=\"selected\"";
                }
                $html .= ">" . $handler['menuitem'] . "</option>";
            }
        }
        $html = "<select name=\"$label\">$html</select";
        return $html;
        
    }
    
    function file_page_owner() {
        
        $owner = null;
        
        $files_name = optional_param('files_name');
        if (!empty($files_name)) {
            $owner = user_info_username('ident', $files_name);
        }
        $fowner = optional_param('files_owner',$owner);
        if (!empty($fowner)) {
            $owner = $fowner;
        }
        if ($owner != null) {
            return $owner;
        }

    }
    

?>
