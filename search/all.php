<?php

//    ELGG search through everything page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

run("search:init");
run("search:all:tagtypes");

define("context","search");

$tag = optional_param('tag');
$title = __gettext("Searching") . " : " . $tag;

templates_page_setup();
$body = run("content:search:all");
$body .= run("search:all:display", $tag);

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