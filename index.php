<?php

    global $CFG;

    define('context', 'external');
    require_once(dirname(__FILE__)."/includes.php");

    $context = isloggedin() ? 'frontpage_loggedin' : 'frontpage_loggedout';
    $title = $CFG->sitename;
 
    templates_page_setup();
    templates_page_output($title, null, $context);

?>