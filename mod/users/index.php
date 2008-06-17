<?php

//    ELGG change user details page

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

run("profile:init");
run("userdetails:init");

$context = optional_param('context','account');
define('context',$context);

require_login();

templates_page_setup();

$title = run("profile:display:name") . " :: ". __gettext("Edit user details");
$body = run('userdetails:edit');

templates_page_output($title, $body);

?>