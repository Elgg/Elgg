<?php
global $CFG, $USER;

// Action parser for file uploads

global $folder;
global $page_owner;
$textlib = textlib_get_instance();

$action = optional_param('action');
if (logged_on) {
    switch ($action) {
        
    // Create a new folder
    case "files:createfolder":
        $f = new StdClass;
        $f->name = trim(optional_param('new_folder_name'));
        $f->access = trim(optional_param('new_folder_access'));
        if (!empty($f->access) && !empty($folder) && run("permissions:check", "files")) {
            $f->parent = $folder;
            $f->files_owner = $page_owner;
            $f->owner = $USER->ident;
            $f->handler = trim(optional_param('edit_folder_type'));
            $f = plugin_hook("folder","create",$f);
            if (!empty($f)) {
                $insert_id = insert_record('file_folders',$f);
                $f->ident = $insert_id;
                $value = trim(optional_param('new_folder_keywords'));
                insert_tags_from_string ($value, 'folder', $insert_id, $f->access, $f->owner);
                plugin_hook("folder","publish",$f);
                $messages[] = __gettext("Your folder was created.");
            }
        } else {
            $messages[] = __gettext("Could not create folder. Perhaps the folder name was blank?");
        }
        break;
                
        // Upload a new file
    case "files:uploadfile":
        $description = trim(optional_param('new_file_description'));
        $title = trim(optional_param('new_file_title'));
        $access = trim(optional_param('new_file_access'));
        $folderid = optional_param('folder',0,PARAM_INT);
        $copyright = optional_param('copyright');
        
        if (logged_on && !empty($access) && !empty($folderid) && run("permissions:check", "files")) {
            $ul_username = user_info('username', $page_owner);
            if (empty($copyright)) {
                $redirect_url = $CFG->wwwroot . $ul_username . "/files/";
                if ($folderid > -1) {
                    $redirect_url .= $folderid;
                }
                define('redirect_url', $redirect_url);
                $messages[] = __gettext("Upload unsuccessful. You must check the copyright box for a file to be uploaded.");
                break;
            }
            $upload_folder = $textlib->substr($ul_username,0,1);
            require_once($CFG->dirroot.'lib/uploadlib.php');
            $total_quota = get_field_sql('SELECT sum(size) FROM '.$CFG->prefix.'files WHERE owner = ?',array($page_owner));
            $max_quota = user_info('file_quota',$page_owner);
            $maxbytes = $max_quota - $total_quota;
            $um = new upload_manager('new_file',false,true,false,$maxbytes,true);
            $reldir =  "files/" . $upload_folder . "/" . $ul_username . "/"; 
            $dir = $CFG->dataroot .$reldir;
            if ($um->process_file_uploads($dir)) {
                $f = new StdClass;
                $f->owner = $USER->ident;
                $f->files_owner = $page_owner;
                $f->folder =  $folderid;
                $f->originalname = $um->get_original_filename();
                $f->title = $title;
                $f->description = $description;
                $f->location = $reldir . $um->get_new_filename();
                $f->access = $access;
                $f->size = $um->get_filesize();
                $f->time_uploaded = time();
                $f = plugin_hook("file","create",$f);
                if (!empty($f)) {
                    $file_id = insert_record('files',$f);
                    $f->ident = $file_id;
                    $value = trim(optional_param('new_file_keywords'));
                    insert_tags_from_string ($value, 'file', $file_id, $access, $page_owner);
                    $metadata = optional_param('metadata');
                    if (is_array($metadata)) {
                        foreach($metadata as $name => $value) {
                            $m = new StdClass;
                            $m->name = trim($name);
                            $m->value = trim($value);
                            $m->file_id = $file_id;
                            insert_record('file_metadata',$m);
                        }
                    }
                    plugin_hook("file","publish",$f);
                    $rssresult = run("files:rss:publish", array($page_owner, false));
                    $rssresult = run("profile:rss:publish", array($page_owner, false));
                    $messages[] = __gettext("The file was successfully uploaded.");
                }
            } else {
                $messages[] = $um->get_errors();
            }
            
            $redirect_url = $CFG->wwwroot . $ul_username . "/files/";
            if ($folderid > -1) {
                $redirect_url .= $folderid;
            }
            define('redirect_url', $redirect_url);
        }
        break;
        
        // Edit a file
    case "files:editfile":
        $f = new stdClass;
        $f->ident = optional_param('file_id',0,PARAM_INT);
        $f->title = trim(optional_param('edit_file_title'));
        $f->folder = optional_param('edit_file_folder',0,PARAM_INT);
        $f->access = trim(optional_param('edit_file_access'));
        $f->description = trim(optional_param('edit_file_description'));
        if (!empty($f->ident) && !empty($f->folder) && !empty($f->access)) {
            $file_info = get_record('files','ident',$f->ident);
            if (!empty($file_info) && run("permissions:check", array("files:edit",$file_info->files_owner))) {
                $files_username = user_info('username', $file_info->files_owner);
                $f = plugin_hook("file","update",$f);
                if (!empty($f)) {
                    update_record('files',$f);
                    $file_info = get_record('files','ident',$f->ident);
                    delete_records('tags','tagtype','file','ref',$f->ident);
                    $file_keywords = trim(optional_param('edit_file_keywords'));
                    insert_tags_from_string ($file_keywords, 'file', $f->ident, $f->access, $USER->ident);
                    plugin_hook("file","republish",$file_info);
                    $redirect_url = $CFG->wwwroot . $files_username . "/files/";
                    if ($f->folder != -1) {
                        $redirect_url .= $f->folder;
                    }
                    define('redirect_url',$redirect_url);
                    $rssresult = run("files:rss:publish", array($file_info->files_owner, false));
                    $rssresult = run("profile:rss:publish", array($file_info->files_owner, false));
                    $messages[] = __gettext("The file was updated.");
                }
            }
        }
        break;
        
        // Edit a folder
    case "edit_folder":
        $f = new StdClass;
        $return_type = trim(optional_param('return_type','same'));
        $f->ident = optional_param('edit_folder_id',0,PARAM_INT);
        $f->name = trim(optional_param('edit_folder_name'));
        $f->access = trim(optional_param('edit_folder_access'));
        $f->handler = trim(optional_param('edit_folder_type'));
        $f->parent = optional_param('edit_folder_parent',0,PARAM_INT);
        if (!empty($f->ident) && !empty($f->name) && !empty($f->access) && !empty($f->parent)) {
            $edit_owner = get_field('file_folders','files_owner','ident',$f->ident);
            if (run("permissions:check", array("files:edit",$edit_owner))) {
                if ($f->ident != $f->parent) {
                    $f = plugin_hook("folder","update",$f);
                    if (!empty($f)) {
                        update_record('file_folders',$f);
                        delete_records('tags','tagtype','folder','ref',$f->ident);
                        $edit_value = trim(optional_param('edit_folder_keywords'));
                        insert_tags_from_string ($edit_value, 'folder', $f->ident, $f->access, $USER->ident);
                        plugin_hook("folder","updated",$f);
                        $messages[] = __gettext("The folder was edited.");
                        if ($return_type == "same") {
                            $return_url = $CFG->wwwroot . user_info("username",$edit_owner) . "/files/" . $f->ident;
                        } else {
                            $return_url = $CFG->wwwroot . user_info("username",$edit_owner) . "/files/";
                            if ($f->parent > -1) {
                                $return_url .= $f->parent;
                            }
                        }
                        $_SESSION['messages'] = $messages;
                        header("Location: $return_url");
                        exit;
                    }
                } else {
                    $messages[] = __gettext("Error: a folder cannot be its own parent.");
                }
            }
        }
        break;

        // Delete a folder
    case "delete_folder":
        $id = optional_param('delete_folder_id',0,PARAM_INT);
        if (!empty($id) && $id != -1) {
            $folder = get_record('file_folders','ident',$id);
            if (!empty($folder) && (run("permissions:check", array("files:edit",$folder->files_owner)) || run("permissions:check", array("files:edit",$folder->owner)))) {
                $files_username = user_info('username', $folder->files_owner);
                $folder = plugin_hook("folder","delete",$folder);
                if (!empty($folder)) {
                    set_field('file_folders','parent',$folder->parent,'parent',$id);
                    set_field('files','folder',$folder->parent,'folder',$id);
                    delete_records('file_folders','ident',$id);
                    delete_records('tags','tagtype','folder','ref',$id);
                }
                global $redirect_url;
                $redirect_url = $CFG->wwwroot . $files_username . "/files/";
                if ($folder->parent > -1) {
                    $redirect_url .= $folder->parent;
                }
                define('redirect_url', $redirect_url);
                $messages[] = __gettext("The folder was deleted.");
            }
        }
        break;
    
        // Delete a file
    case "delete_file":
        $id = optional_param('delete_file_id',0,PARAM_INT);
        if (!empty($id) && $id != -1) {
            $file = get_record('files','ident',$id);

            if (!empty($file) && (run("permissions:check", array("files:edit",$file->files_owner)) || run("permissions:check", array("files:edit",$file->owner)))) {
                $files_username = user_info('username', $file->files_owner);
                $file = plugin_hook("file","delete",$file);
                if (!empty($file)) {
                    @unlink(stripslashes($CFG->dataroot.$file->location)); //TODO maybe some error reporting here!?! Penny
                    delete_records('files','ident',$id);
                    delete_records('tags','tagtype','file','ref',$id);
                }
                $redirect_url = $CFG->wwwroot . $files_username . "/files/";
                if ($file->folder > -1) {
                    $redirect_url .= $file->folder;
                }
                define('redirect_url', $redirect_url);
                $rssresult = run("files:rss:publish", array($file->files_owner, false));
                $rssresult = run("profile:rss:publish", array($file->files_owner, false));
                $messages[] = __gettext("The file was deleted.");

            }
        }
        break;
    }
    
    
}

?>