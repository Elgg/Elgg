<?php

//    ELGG template create / select page

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

require_login();

run("profile:init");
run("templates:init");

$title = __gettext("Template Preview");
define("context", "account");

templates_page_setup();

$body = templates_preview(); 

global $messages;
$messages[] = "System message 1";
$messages[] = "System message 2";

templates_page_output($title, $body, false);

?>