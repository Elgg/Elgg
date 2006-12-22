<?php

function file_init() {
    global $CFG;
    
    // Has the $CFG->files->default_handler been set? If not, set it to local
        if (empty($CFG->files->default_handler)) {
            $CFG->files->default_handler = "elgg";
        }
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
            if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
                $PAGE->menu_sub[] = array( 'name' => 'file:help',
                                           'html' => a_href( $CFG->wwwroot."help/files_help.php",
                                                              __gettext("Page help")));  
            }
        }
    }
}

?>
