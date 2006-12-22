<?php

//    ELGG manage icons page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

// Initialise functions for user details, icon management and profile management
run("userdetails:init");
run("profile:init");
run("icons:init");

$context = optional_param('context','account');
define('context',$context);

// You must be logged on to view this!
protect(1);
templates_page_setup();

$title = run("profile:display:name") . " :: ". __gettext("Manage user icons");

$body = run("content:icons:manage");
$body .= run("icons:edit");
$body .= run("icons:add");

$mainbody = templates_draw(array(
                                 'context' => 'contentholder',
                                 'title' => $title,
                                 'body' => $body
                                 )
                           );
                           
echo templates_page_draw( array(
                                      $title, $mainbody
                                      )
         );
                                    
?>