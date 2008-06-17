<?php
global $CFG,$USER;
// Action parser for icons
$textlib = textlib_get_instance();
global $page_owner;

$action = optional_param('action');
if ($action == "icons:edit" && logged_on && run("permissions:check", "uploadicons")) {
    
    // Set a new default!
    $icondefault = optional_param('defaulticon',0,PARAM_INT);
    if ($icondefault == -1) {
        set_field('users','icon',-1,'ident',$page_owner);
        $USER->icon = -1; //id
        $_SESSION['icon'] = "default.png"; //filename
    } else {
        if ($iconfilename = get_field('icons','filename','ident',$icondefault,'owner',$page_owner)) {
            if ($page_owner == $USER->ident) {
                $_SESSION['icon'] = $iconfilename; //filename
                $USER->icon = $icondefault; //id
            }
            set_field('users','icon',$icondefault,'ident',$page_owner);
        }
    }
    
    // Change their descriptions!
    $description = optional_param('description',array());
    foreach($description as $iconid => $newdescription) {
        $iconid = clean_param($iconid,PARAM_INT);
        $newdescription = trim($newdescription);
        if ($result = get_record('icons','ident',$iconid,'owner',$page_owner)) {
            if ($result->description != $newdescription) {
                set_field('icons','description',$newdescription,'ident',$iconid);
            }
        }
    }
    
    // Delete them!
    $icons_delete = optional_param('icons_delete',array(),PARAM_INT);
    if (count($icons_delete) > 0) {
        foreach($icons_delete as $delete_icon) {
            if ($result = get_record('icons','ident',$delete_icon,'owner',$page_owner)) {
                delete_records('icons','ident',$delete_icon);
                set_field('weblog_posts', 'icon', -1, 'icon', $delete_icon, 'owner', $page_owner);
                $ul_username = user_info('username', $page_owner);
                $upload_folder = $textlib->substr($ul_username,0,1);
                $filepath = $CFG->dataroot . "icons/" . $upload_folder . "/" . $ul_username . "/".$result->filename; 
                @unlink($filepath);
                if ($result->filename == $USER->icon) {
                    set_field('users','icon',-1,'ident',$page_owner);
                    if ($page_owner == $USER->ident) {
                        $USER->icon = -1; //id
                        $_SESSION['icon'] = "default.png"; //filename
                    }
                }
            }
            $messages[] = __gettext("Your selected icons were deleted.");
        }
    }
}

if ($action == "icons:add" && logged_on && run("permissions:check", "uploadicons")) {
    $description = optional_param('icondescription');
    $icondefault = optional_param('icondefault');
    // if (!empty($description)) {
        $ok = true;
        if ($ok == true) {
            $numicons = count_records('icons','owner',$page_owner);
            if ($numicons >= $_SESSION['icon_quota']) {
                $ok = false;
                $messages[] = __gettext("You have already met your icon quota. You must delete some icons before you can upload any new ones.");
            }
        }
        require_once($CFG->dirroot.'lib/uploadlib.php');
        // TODO passing 0 as maxbytes here as icon_quota is based on number of icons
        // so upload_manager will look at PHP settings instead.
        // not ideal but as good as it can be for the now.
        $um = new upload_manager('iconfile',false,true,false,0,true);
        $messages[] = __gettext("Attempting to upload icon file ...");
        $ul_username = user_info('username', $page_owner);
        $upload_folder = $textlib->substr($ul_username,0,1);
        $dir = $CFG->dataroot . "icons/" . $upload_folder . "/" . $ul_username . "/"; 
        if ($ok = $um->process_file_uploads($dir)) {
            if (!$imageattr = @getimagesize($um->get_new_filepath())) {
                $ok = false;
                $messages[] = __gettext("The uploaded icon file was invalid. Please ensure you are using JPEG, GIF or PNG files.");
            }
        }
        if ($ok == true) {
            if ($imageattr[0] > 100 || $imageattr[1] > 100) {
                // $ok = false;
                // $messages[] = __gettext("The uploaded icon file was too large. Files must have maximum dimensions of 100x100.");
                require_once($CFG->dirroot . 'lib/iconslib.php');
                $phpThumb = new phpThumb();
                // import default config
                if (!empty($PHPTHUMB_CONFIG)) {
                    foreach ($PHPTHUMB_CONFIG as $key => $value) {
                        $keyname = 'config_'.$key;
                        $phpThumb->setParameter($keyname, $value);
                    }
                }
                $phpThumb->setSourceFilename($um->get_new_filepath());
                $phpThumb->w = 100;
                $phpThumb->h = 100;
                $phpThumb->config_output_format = 'jpeg';
                $phpThumb->config_error_die_on_error = false;
                if ($phpThumb->GenerateThumbnail()) {
                    $phpThumb->RenderToFile($um->get_new_filepath());
                    $imageattr[2] = "2";
                } else {
                    $ok = false;
                    $messages[] .= '#Failed: '.implode("<br />", $phpThumb->debugmessages);
                    unlink($um->get_new_filepath()); // it failed, so delete it.
                }
            }
        }
        if ($ok == true && ($imageattr[2] > 3 || $imageattr[2] < 1)) {
            $message[] = __gettext("The uploaded icon file was in an image format other than JPEG, GIF or PNG. These are unsupported at present.");
        } else if ($ok == true) {
            switch($imageattr[2]) {
            case "1":    $file_extension = ".gif";
                break;
            case "2":    $file_extension = ".jpg";
                break;
            case "3":    $file_extension = ".png";
                break;
            }
            $i = new StdClass;
            $i->filename = $um->get_new_filename();
            $i->owner = $page_owner;
            $i->description = $description;
            $ident = insert_record('icons',$i);
            if ($icondefault == "yes") {
                set_field('users','icon',$ident,'ident',$page_owner);
                if ($page_owner == $USER->ident) {
                    $_SESSION['icon'] = $i->filename; //filename
                    $USER->icon = $ident; //id
                    unset($_SESSION['user_info_cache'][$USER->ident]);
                }
            }
            $messages[] = __gettext("Your icon was uploaded successfully.");
            
        } else {
            $messages[] = __gettext("An unknown error occurred when saving your icon. If this problem persists, please let us know and we'll do all we can to fix it quickly.");
        }
    // }
}

?>
