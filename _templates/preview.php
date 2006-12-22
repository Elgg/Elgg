<?php

//    ELGG template create / select page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

protect(1);

run("profile:init");
run("templates:init");

$title = __gettext("Template Preview");
define("context", "account");

templates_page_setup();

$body = templates_preview(); 

global $messages;
$messages[] = "System message 1";
$messages[] = "System message 2";

echo templates_page_draw( array(
                                $title, $body
                                )
                          );

?>