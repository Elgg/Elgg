<?php

// Plug-in engine intialisation routines

// Global log arrays
global $log;
global $errorlog;
global $actionlog;
$log = array();
$errorlog = array();
$actionlog = array();

// Message arrays
global $messages;
if (empty($messages)) { // might be set up already...
    $messages = array();
}

// Add the site root to the metatags
global $metatags;
$metatags .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
        // $metatags .= "     <base href=\"".url."\" />";

// Set default charset to UTF-8
@ini_set("default_charset","UTF-8");
header("Content-Type: text/html; charset=utf-8");

?>