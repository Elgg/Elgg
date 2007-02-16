<?php

function file_init() {
    global $CFG;
    
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
}

function file_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;

    if (isloggedin() && user_info("user_type",$_SESSION['userid']) != "external") {
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
        
        if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
            $PAGE->menu_sub[] = array( 'name' => 'file:add',
                                       'html' => a_href( "#addFile",
                                                          __gettext("Add a file or a folder")));
        }
        if ($page_owner != -1) {
            if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
                $PAGE->menu_sub[] = array( 'name' => 'file:rss',
                                           'html' => a_href( $CFG->wwwroot.$_SESSION['username']."/files/rss/", 
                                                              __gettext("RSS feed for files")));  
            }
        }
    }
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
                                                  'icon' => $CFG->wwwroot . "_files/folder.png",
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
                    require_once($CFG->dirroot.'lib/filelib.php');
                    $mimetype = mimeinfo('type',$file->originalname);
                    if ($mimetype == "audio/mpeg" || $mimetype == "audio/mp3") {
                        $filemenu .= " <object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"
        codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\"
        width=\"17\" height=\"17\" >
            <param name=\"allowScriptAccess\" value=\"sameDomain\" />
            <param name=\"movie\" value=\"" . $CFG->wwwroot . "_files/mp3player/musicplayer.swf?song_url=$filepath&amp;song_title=$filetitle\" />
            <param name=\"quality\" value=\"high\" />
            <embed src=\"" . $CFG->wwwroot . "_files/mp3player/musicplayer.swf?song_url=$filepath&amp;song_title=$filetitle\"
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
        if (run("permissions:check", array("files:edit", $file->owner))  || run("permissions:check", array("files:edit", $file->files_owner))) {
                        $areyouSure = __gettext("Are you sure you want to permanently delete this file?"); // gettext variable
                        $delete = __gettext("Delete"); // gettext variable
                        $edit = __gettext("Edit"); // gettext variable
                        $filemenu .= <<< END
            [<a href="{$CFG->wwwroot}_files/edit_file.php?edit_file_id={$file->ident}&amp;owner=$page_owner">$edit</a>]
            [<a href="{$CFG->wwwroot}_files/action_redirection.php?action=delete_file&amp;delete_file_id={$file->ident}" onclick="return confirm('$areyouSure')">$delete</a>]
END;
        }
        return $filemenu;
        
    }

    function file_folder_edit_links($folder) {

        global $page_owner, $CFG;
        $foldermenu = "";
                
        if (run("permissions:check", array("files:edit", $folder->owner))  || run("permissions:check", array("files:edit", $folder->files_owner))) {
            $areyouSure = __gettext("Are you sure you want to permanently delete this folder?"); // gettext variable
            $delete = __gettext("Delete"); // gettext variable
            $edit = __gettext("Edit"); // gettext variable
            $foldermenu = <<< END
            [<a href="{$CFG->wwwroot}_files/edit_folder.php?edit_folder_id={$folder->ident}&amp;owner=$page_owner&amp;return_type=parent">$edit</a>]
            [<a href="{$CFG->wwwroot}_files/action_redirection.php?action=delete_folder&amp;delete_folder_id={$folder->ident}" onclick="return confirm('$areyouSure')">$delete</a>]
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
