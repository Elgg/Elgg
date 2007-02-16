<?php

/*
 *    View files
 */

// Get owner and current folder
    
global $owner, $folder, $CFG;

// Get folder
// $folder_object = get_record('file_folders','files_owner',$owner,'ident',$folder);
$folder_object = $parameter;

// Check to ensure we have access to this folder, if we're not in the root
$accessible = false;
if ($folder != -1) {
    if ($access = $folder_object->access) {
        $accessible = run("users:access_level_check",$access);
    }
} else {
    $accessible = true;
    $folder_object = new stdClass();
    $folder_object->ident = -1;
    $folder_object->handler = "elgg";
    $folder_object->name = __gettext("Root Folder");
}

if (!isset($folder_object->handler) 
    || !@is_callable($CFG->folders->handler[$folder_object->handler]['view'])) {
    $folder_object->handler = "elgg";
}

if ($folder_object->ident != -1) {            
    $parent = (int) $folder_object->parent;
    
    if ($parent != -1) {
        $parent_details = get_record('file_folders','ident',$parent,'files_owner',$owner);
        $display_parent = $parent;
    } else {
        $parent_details->name = "root folder";
        $parent_details->ident = -1;
        $display_parent = "";
    }
    
    $run_result .= "<p><a href=\"".url.user_info("username",$owner)."/files/$display_parent\">";
    $run_result .= "". __gettext("Return to") ." " . stripslashes($parent_details->name);
    $run_result .= "</a></p>";
}
        
// If we're in the root or an accessible folder, view it
if ($accessible) {
    if (!isset($folder_object->handler) || !isset($CFG->folders->handler[$folder_object->handler])) {
        $run_result .= run("files:folder:view",$folder);
    } else if (is_callable($CFG->folders->handler[$folder_object->handler]['view'])) {
        $run_result .= $CFG->folders->handler[$folder_object->handler]['view']($folder_object);
    }
}
        
// If this is the user's own file repository, allow him or her to edit it

if (run("permissions:check", "files")) {
    
    $run_result .= run("files:folder:edit",$folder);
    
} else {
    
    //
    
}
    
?>