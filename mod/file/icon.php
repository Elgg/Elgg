<?php

// Icon script

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

// Initialise functions for user details, icon management and profile management
run("userdetails:init");
run("profile:init");
run("files:init");

global $CFG;

// If an ID number for the file has been specified ...
$id = optional_param('id',0,PARAM_INT);
$w = optional_param('w',90,PARAM_INT);
$h = optional_param('h',90,PARAM_INT);

if (!empty($id)) {
    // ... and the file exists ...
    if ($file = get_record('files','ident',$id)) {
        if ($file->access == 'PUBLIC' || run("users:access_level_check",$file->access) == true) {
            
            require_once($CFG->dirroot . 'lib/filelib.php');
            require_once($CFG->dirroot . 'lib/iconslib.php');
            
            // images most likely don't want compressing, and this will kill the Vary header
            if (function_exists('apache_setenv')) { // apparently @ isn't enough to make php ignore this failing
                @apache_setenv('no-gzip', '1');
            }
            
            if ($file->access == 'PUBLIC') {
                header("Pragma: public");
                header("Cache-Control: public");
            } else {
                // "Cache-Control: private" to allow a user's browser to cache the file, but not a shared proxy
                // Also to override PHP's default "DON'T EVER CACHE THIS EVER" header
                header("Cache-Control: private");
            }
            
            $mimetype = mimeinfo('type',$file->originalname);
            if ($mimetype == "image/jpeg" || $mimetype == "image/png" || $mimetype == "image/gif") {
                // file is an image
                
                $phpthumbconfig = array();
                $phpthumbconfig['w'] = $w;
                $phpthumbconfig['h'] = $h;
                
                $filelocation = file_cache($file);
                spit_phpthumb_image($filelocation, $phpthumbconfig);
                
            } else {
                
                // file is a file
                header($_SERVER['SERVER_PROTOCOL'] . " 301 Moved Permanently"); //permanent because the user's file can't change and keep the same id
                header("Location: " . $CFG->wwwroot . 'mod/file/file.png');
                die();
                
            }
            
        }
    }
}

?>