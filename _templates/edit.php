<?php

//    ELGG template edit page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

protect(1);

run("profile:init");
run("templates:init");

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

$body = templates_draw(array(
                             'context' => 'contentholder',
                             'title' => $title,
                             'body' => $body
                             )
                       );
                       
echo templates_page_draw( array(
                                      $title, $body
                                      )
         );

?>