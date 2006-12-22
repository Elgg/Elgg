<?php

    /*
    *   XML-RPC entry point
    */

    // TODO include a check for a valid request
 
    // This can be run from walled gardens
        define("context","external");
        
    // Run includes
        require_once(dirname(dirname(__FILE__))."/includes.php");

    // Fire up the XML-RPC server
        run('rpc:xmlrpc:server');

?>
