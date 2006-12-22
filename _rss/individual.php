<?php

require_once(dirname(dirname(__FILE__))."/includes.php");

run("weblogs:init");
run("profile:init");
run("rss:init");

define('context','resources');
global $page_owner;

$feed = optional_param('feed',-1,PARAM_INT);
$title = __gettext("Feed detail");
templates_page_setup();
run("rss:update",$feed);
$body = run("rss:view:feed",$feed);

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