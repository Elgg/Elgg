<?php
// 
// icon functions - just phpthumb handlers for now
//

require_once($CFG->dirroot . 'lib/filelib.php');
require_once($CFG->dirroot . 'lib/phpthumb/phpthumb.class.php');
require_once($CFG->dirroot . 'lib/phpthumb/phpThumb.config.php');


// a cut-down functionised version of the standard phpThumb.php
// outputs an image to the browser and exits
function spit_phpthumb_image($filepath, $configarray = array()) {
    
    // set up class
    global $CFG, $PHPTHUMB_CONFIG;
    $phpThumb = new phpThumb();
    
    // import default config
    if (!empty($PHPTHUMB_CONFIG)) {
        foreach ($PHPTHUMB_CONFIG as $key => $value) {
            $keyname = 'config_'.$key;
            $phpThumb->setParameter($keyname, $value);
        }
    }
    
    // import passed params
    if (!empty($configarray)) {
        foreach ($configarray as $key => $value) {
            $keyname = $key;
            $phpThumb->setParameter($keyname, $value);
        }
    }
    
    $phpThumb->setSourceFilename($filepath);
    
    if (!is_file($phpThumb->sourceFilename) && !phpthumb_functions::gd_version()) {
        if (!headers_sent()) {
            // base64-encoded error image in GIF format
            $ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
            header('Content-Type: image/gif');
            echo base64_decode($ERROR_NOGD);
        } else {
            echo '*** ERROR: No PHP-GD support available ***';
        }
        exit;
    }
    
    $phpThumb->SetCacheFilename();
    
    if (!file_exists($phpThumb->cache_filename) && is_writable(dirname($phpThumb->cache_filename))) {
//         error_log("generating to cache: " . $phpThumb->cache_filename);
        $phpThumb->CleanUpCacheDirectory();
        $phpThumb->GenerateThumbnail();
        $phpThumb->RenderToFile($phpThumb->cache_filename);
    }
    
    if (is_file($phpThumb->cache_filename)) {
//         error_log("sending from cache: " . $phpThumb->cache_filename);
        if ($getimagesize = @GetImageSize($phpThumb->cache_filename)) {
            $mimetype = phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]);
        }
        spitfile_with_mtime_check ($phpThumb->cache_filename, $mimetype);
    } else {
//         error_log("phpthumb cache file doesn't exist: " . $phpThumb->cache_filename);
        $phpThumb->GenerateThumbnail();
        $phpThumb->OutputThumbnail();
        exit;
    }
    
}



?>