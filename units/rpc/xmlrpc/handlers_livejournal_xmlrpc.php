<?php

    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers = array('LJ.XMLRPC.getFriends'  => 'lj_getFriends');

    $RPC->addMapping($handlers);
    $RPC->addLibrary(dirname(__FILE__)."/library_livejournal_xmlrpc.php");

?>
