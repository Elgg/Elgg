<?php

    /*
     *   The XML-RPC server
     */

    /*
        XML_RPC error codes. Not defined in the specs, these are codes used 
        in nucleuscms.org, blogcms.org and others
        
        801  	Login Error (probably bad username/password combination)
        802 	No Such Blog
        803 	Not a Team Member
        804 	Cannot add Empty Items
        805 	Amount parameter must be in range 1..20 (getRecentItems)
        806 	No Such Item
        807 	Not Allowed to Alter Item
        808 	Invalid media type
        809 	File is too large (max. upload filesize)
        810 	Other error on newMediaObject (message will contain more info about what happened)
        other codes < 100 	Errors encountered by the Useful Inc. XML-RPC implementation
        other codes > 100 	Errors encountered by the XML parser
        
        Other commonly used error codes
        
        -32700  parse error. not well formed
        -32701  parse error. unsupported encoding
        -32702  parse error. invalid character for encoding
        -32600  server error. invalid xml-rpc. not conforming to spec.
        -32601  server error. requested method not found
        -32602  server error. invalid method parameters
        -32603  server error. internal xml-rpc error
        -32500  application error
        -32400  system error
        -32300  transport error
    */

    require_once "XML/RPC/Server.php";

    // The global handlers array
    
    global $handlers;

    $handlers = array();

    // The XML-RPC API's

    include path . "units/rpc/xmlrpc/library_blogger_xmlrpc.php";
    include path . "units/rpc/xmlrpc/library_mt_xmlrpc.php";
    include path . "units/rpc/xmlrpc/library_livejournal_xmlrpc.php";

    // Fire up the server
    $server = new XML_RPC_Server($handlers, 0);
    
    // Possibility for handling other stuff here
    
    // Serve the request
    $server->service();

?>
