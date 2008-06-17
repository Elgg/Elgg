<?php

// User icon serving script.
// Usage: http://URL/_icon/user/{icon_id}

// Run includes
define("context","icons");
require_once(dirname(dirname(__FILE__))."/../includes.php");

// If an ID number for the file has been specified ...
$id = optional_param('id',0,PARAM_INT);
$default = false;
if (!$id) {
    $default = true;
}
// ... and the file exists ...
if (!$file = get_record('icons','ident',$id)) {
    $default = true;
}
// get the user who belongs to this icon..
if (empty($file) || !$user = get_record('users','ident',$file->owner)) {
    $default = true;
}
if (!$default) {
    $upload_folder = substr($user->username,0,1);
    $filepath = $CFG->dataroot . "icons/" . $upload_folder . "/" . $user->username . "/".$file->filename;
    if (!file_exists($filepath)) {
        $default = true;
    }
}

require_once($CFG->dirroot . 'lib/filelib.php');
require_once($CFG->dirroot . 'lib/iconslib.php');

if ($default) {
    $filepath = $CFG->dirroot.'mod/icons/data/default.png';
    $mimetype = 'image/png';
} else {
    $mimetype = mimeinfo('type', $file->filename);
}


// Then output some appropriate headers and send the file data!

// see if we must resize it.
$constraint1 = strtolower(optional_param('constraint1'));
$size1 = optional_param('size1', PARAM_INT);
$constraint2 = strtolower(optional_param('constraint2'));
$size2 = optional_param('size2', PARAM_INT);

// if size == 100, leave it.
$phpthumb = false;
$phpthumbconfig = array();
if (($constraint1 == 'h' || $constraint1 == 'w') && $size1 != 100) {
    $phpthumb = true;
    $phpthumbconfig[$constraint1] = $size1;
}
if (($constraint2 == 'h' || $constraint2 == 'w') && $size2 != 100) {
    $phpthumb = true;
    $phpthumbconfig[$constraint2] = $size2;
}

// images most likely don't want compressing, and this will kill the Vary header
if (function_exists('apache_setenv')) { // apparently @ isn't enough to make php ignore this failing
    @apache_setenv('no-gzip', '1');
}

// user icons are public
header("Pragma: public");
header("Cache-Control: public");

if (!$default && !$phpthumb && ($constraint1 == 'h' || $constraint1 == 'w') && (!$constraint2 || $constraint2 == 'h' || $constraint2 == 'w')) {
    // 100 pixels requested, redirect to attributeless icon url for cacheability fun
    header($_SERVER['SERVER_PROTOCOL'] . " 301 Moved Permanently");
    header("Location: " . $CFG->wwwroot . '_icon/user/' . $id);
    die();
}

if ($phpthumb) {
    // let phpthumb manipulate the image
    spit_phpthumb_image($filepath, $phpthumbconfig);
} elseif ($default) {
    // no manipulation and default icon
    if ($id == -1) {
        header($_SERVER['SERVER_PROTOCOL'] . " 301 Moved Permanently");
    }
    header("Location: " . $CFG->wwwroot . 'mod/icons/data/default.png');
    die();
} else {
    // output the image directly
    spitfile_with_mtime_check ($filepath, $mimetype);
}

?>