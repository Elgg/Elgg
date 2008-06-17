<?php

//    ELGG manage icons page

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

// Initialise functions for user details, icon management and profile management
run("userdetails:init");
run("profile:init");
run("icons:init");

require_login();

$context = optional_param('context','account');
define('context',$context);

templates_page_setup();

$title = run("profile:display:name") . " :: ". __gettext("Manage user icons");

$body = run("content:icons:manage");
$body .= run("icons:edit");
$body .= run("icons:add");

templates_page_output($title, $body);

?>