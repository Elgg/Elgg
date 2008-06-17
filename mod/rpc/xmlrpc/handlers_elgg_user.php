<?php

    // Prepare a list of handlers to be loaded into the XML-RPC server
    $handlers = array('elgg.user.getUserIcon' => 'getUserIcon');

    // Add the handlers to the global handlers array
    $RPC->addMapping($handlers);
    $RPC->addLibrary(dirname(__FILE__)."/library_elgg_user.php");

?>
