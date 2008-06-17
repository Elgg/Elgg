<?php

//    ELGG template edit page

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

run("profile:init");
run("templates:init");

require_login();

define("context", "account");
templates_page_setup();
$title = run("profile:display:name") . " :: " . __gettext("Template Edit");

$body = run("content:templates:edit");

$id = optional_param('id',0,PARAM_INT);
if (!empty($id)) {
    $body .= run("templates:edit",$id);
} else {
    $body = run("templates:edit");
}

templates_page_output($title, $body);

?>