<?php
global $CFG;
/*
 *    View a specific folder
 *    (Access rights are presumed)
 */

// If a folder has been specified, convert it to integer;
// otherwise assume we're in the root

if (isset($parameter) && $parameter != "") {
    $folder = (int) $parameter;
} else {
    $folder = -1;
}

// Find out who's the owner
    
global $page_owner;
$owner_username = user_info('username', $page_owner);

// If we're not in the parent folder, provide a link to return to the parent
    
global $this_folder;
global $folder_name;

$folder_name = __gettext("Root Folder");
if ($folder != -1) {
    if ($this_folder = get_record('file_folders','ident',$folder,'files_owner',$page_owner)) {
        $folder_name = stripslashes($this_folder->name);
    }
}
        
$body = "<h2>" . $folder_name . "</h2>";

if ($folder != -1) {
    
    $parent = (int) $this_folder->parent;
    
    if ($parent != -1) {
        $parent_details = get_record('file_folders','ident',$parent,'files_owner',$page_owner);
        $display_parent = $parent;
    } else {
        $parent_details->name = "root folder";
        $parent_details->ident = -1;
        $display_parent = "";
    }
    
    $body .= "<p><a href=\"".url."$owner_username/files/$display_parent\">";
    $body .= "". __gettext("Return to") ." " . stripslashes($parent_details->name);
    $body .= "</a></p>";
}

// Firstly, get a list of folders
// Display folders we actually have access to
if ($folders = get_records_select('file_folders',"parent = $folder AND (". run("users:access_level_sql_where") . ") and files_owner = $page_owner")) {
    $subFolders = __gettext("Subfolders"); // gettext variable
    $body .= <<< END
        
                    <h3>
                        $subFolders
                    </h3>

END;
    
    foreach($folders as $folder_details) {
        
        if (run("users:access_level_check",$folder_details->access) == true) {
            $username = $owner_username;
            $ident = (int) $folder_details->ident;
            $name = get_access_description($folder_details->access);
            $name .= stripslashes($folder_details->name);
            if (run("permissions:check", array("files:edit", $folder_details->owner))  || run("permissions:check", array("files:edit", $folder_details->files_owner))) {
                $areyouSure = __gettext("Are you sure you want to permanently delete this folder?"); // gettext variable
                $delete = __gettext("Delete"); // gettext variable
                $edit = __gettext("Edit"); // gettext variable
                $foldermenu = <<< END
    [<a href="{$CFG->wwwroot}_files/edit_folder.php?edit_folder_id={$folder_details->ident}&amp;owner=$page_owner">$edit</a>]
    [<a href="{$CFG->wwwroot}_files/action_redirection.php?action=delete_folder&amp;delete_folder_id={$folder_details->ident}" onclick="return confirm('$areyouSure')">$delete</a>]

END;
            } else {
                $foldermenu = "";
            }
            $keywords = display_output_field(array("","keywords","folder","folder",$ident,$folder_details->owner));
            if ($keywords) {
                $keywords = __gettext("Keywords: ") . $keywords;
            }
            $body .= templates_draw(array(
                                          'context' => 'folder',
                                          'username' => $username,
                                          'url' => $CFG->wwwroot . "$username/files/$ident",
                                          'ident' => $ident,
                                          'name' => $name,
                                          'menu' => $foldermenu,
                                          'icon' => $CFG->wwwroot . "_files/folder.png",
                                          'keywords' => $keywords
                                          )
                                    );
        }
        
    }
}
    
// Then get a list of files
// View files we actually have access to
if ($files = get_records_select('files',"folder = ? AND files_owner = ?",array($folder,$page_owner))) {
    foreach($files as $file) {
        
        if (run("users:access_level_check",$file->access) == true || $file->owner == $_SESSION['userid']) {
            $username = $owner_username;
            $ident = (int) $file->ident;
            $folder = $file->folder;
            $title = get_access_description($file->access);
            $title .= stripslashes($file->title);
            $description = nl2br(stripslashes($file->description));
            $filetitle = urlencode($title);
            $originalname = stripslashes($file->originalname);
            $filemenu = round(($file->size / 1000000),4) . "Mb ";
            $icon = $CFG->wwwroot . "_icon/file/" . $file->ident;
            $filepath = $CFG->wwwroot . "$username/files/$folder/$ident/" . urlencode($originalname);
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
            if (run("permissions:check", array("files:edit", $file->owner))  || run("permissions:check", array("files:edit", $file->files_owner))) {
                $areyouSure = __gettext("Are you sure you want to permanently delete this file?"); // gettext variable
                $delete = __gettext("Delete"); // gettext variable
                $edit = __gettext("Edit"); // gettext variable
                $filemenu .= <<< END
    [<a href="{$CFG->wwwroot}_files/edit_file.php?edit_file_id={$file->ident}&amp;owner=$page_owner">$edit</a>]
    [<a href="{$CFG->wwwroot}_files/action_redirection.php?action=delete_file&amp;delete_file_id={$file->ident}" onclick="return confirm('$areyouSure')">$delete</a>]
END;
            }
            $keywords = display_output_field(array("","keywords","file","file",$ident,$file->owner));
            if ($keywords) {
                $keywords = __gettext("Keywords: ") . $keywords;
            }
            $body .= templates_draw(array(
                                          'context' => 'file',
                                          'username' => $username,
                                          'title' => $title,
                                          'ident' => $ident,
                                          'folder' => $folder,
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

if (empty($files) && empty($folders)) {
    
    $body .= "<p>" . __gettext("This folder is currently empty.") . "</p>";
    
}

$run_result .= $body;
        
?>
