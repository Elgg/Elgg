<?php

    /*
    *   XML-RPC entry point
    */

    // TODO include a check for a valid request
 
    // Run includes
        require("../includes.php");

    // Fire up the XML-RPC server
        run('rpc:xmlrpc:server');

?>
