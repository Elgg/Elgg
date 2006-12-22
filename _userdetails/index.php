<?php

//    ELGG change user details page

// Run includes
require_once(dirname(dirname(__FILE__))."/includes.php");

run("profile:init");
run("userdetails:init");

$context = optional_param('context','account');
define('context',$context);

protect(1);

templates_page_setup();

$title = run("profile:display:name") . " :: ". __gettext("Edit user details");

$body = templates_draw(array(
                             'context' => 'contentholder',
                             'title' => $title,
                             'body' => run("userdetails:edit")
                             )
                       );
        
echo templates_page_draw( array(
                                      $title, $body
                                      )
         );

?>