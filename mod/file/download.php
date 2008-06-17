<?php

// Download script
// Usage: http://URL/{username}/files/{folder_id}/{file_id}/{filename}

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

// Initialise functions for user details, icon management and profile management
run("userdetails:init");
run("profile:init");
run("files:init");

// If an ID number for the file has been specified ...
$id = optional_param('id',0,PARAM_INT);
if (!empty($id)) {
    // ... and the file exists in the database ...
    if ($file = get_record('files','ident',$id)) {
        // ... and the owner of the file in the URL line hasn't been spoofed ...
        $files_name = optional_param('files_name');
        $userid = user_info_username('ident', $files_name);
        if ($userid == $file->owner || $userid == $file->files_owner) {
            
            // ... and the current user is allowed to access it ...
            if ($file->access == 'PUBLIC' || $file->owner == $_SESSION['userid'] || run("users:access_level_check",$file->access) == true) {
                
                // Then output some appropriate headers and send the file data!
                // TODO: bug on ie, if using ssl force public cache control
                //       using port, $_SERVER['HTTPS'] does not work always
                if ($file->access == 'PUBLIC' || isset($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443) {
                    header("Pragma: public");
                    header("Cache-Control: public");
                } else {
                    // "Cache-Control: private" to allow a user's browser to cache the file, but not a shared proxy
                    // Also to override PHP's default "DON'T EVER CACHE THIS EVER" header
                    header("Cache-Control: private");
                }
                
                require_once($CFG->dirroot . 'lib/filelib.php');
                $mimetype = mimeinfo('type',$file->location);
                
                if ($mimetype == "application/octet-stream") {
                    header('Content-Disposition: attachment');
                }
                
                // disable mod_deflate/mod_gzip for already-compressed files,
                // partly because it's pointless, but mainly because some browsers
                // are thick.
                if (preg_match('#^(application.*zip|image/(png|jpeg|gif))$#', $mimetype)) {
                    if (function_exists('apache_setenv')) { // apparently @ isn't enough to make php ignore this failing
                        @apache_setenv('no-gzip', '1');
                    }
                }
                spitfile_with_mtime_check($CFG->dataroot . $file->location, $mimetype, $file->handler);
                exit;
            }
        }
    }
}

?>