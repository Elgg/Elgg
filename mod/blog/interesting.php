<?php

//    ELGG weblog view page

// Run includes
require_once(dirname(dirname(__FILE__))."/../includes.php");

run("profile:init");
run("weblogs:init");
run("friends:init");

$extensionContext = trim(optional_param('extension','weblog'));

define("context", $extensionContext);
templates_page_setup();

$type = blog_get_extension($extensionContext, 'name');

$title = run("profile:display:name") . " :: ". $type . " :: " . __gettext('Interesting');

$body = run("content:weblogs:view");
$body .= run("weblogs:interesting:view");
$body = '<div id="view_friends_blogs">' . $body . '</div>';

templates_page_output($title, $body);

?>