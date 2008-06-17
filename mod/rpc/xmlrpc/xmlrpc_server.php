<?php

    /*
     *   The XML-RPC server
     */

    /*
        XML_RPC error codes. Not defined in the specs, these are codes used 
        in nucleuscms.org, blogcms.org and others
        
        801      Login Error (probably bad username/password combination)
        802     No Such Blog
        803     Not a Team Member
        804     Cannot add Empty Items
        805     Amount parameter must be in range 1..20 (getRecentItems)
        806     No Such Item
        807     Not Allowed to Alter Item
        808     Invalid media type
        809     File is too large (max. upload filesize)
        810     Other error on newMediaObject (message will contain more info about what happened)
        other codes < 100     Errors encountered by the Useful Inc. XML-RPC implementation
        other codes > 100     Errors encountered by the XML parser
        
        Other commonly used error codes
        
        <http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php>

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

    global $CFG;
    global $RPC;

    require_once $CFG->dirroot . "mod/rpc/lib/IXR_Library.inc.php";

    /**
     *  Elgg class extension to enable:
     *  - delayed excecution
     *  - passing method name to functions 
     */
    Class Elgg_Server extends IXR_Server
    {
        function Elgg_Server($callbacks = false, $data = false)
        {
            $this->setCapabilities();
            if ($callbacks)
            {
                $this->callbacks = $callbacks;
            }
            $this->setCallbacks();
        }

        function serve($data = false)
        {
            parent::serve($data);
        }

        function call($methodname, $args)
        {
            if (!$this->hasMethod($methodname))
            {
                return new IXR_Error(-32601, 'server error. requested method '.$methodname.' does not exist.');
            }
            $method = $this->callbacks[$methodname];
            // Perform the callback and send the response
            if (count($args) == 1)
            {
                // If only one paramater just send that instead of the whole array
                $args = $args[0];
            }
            // Are we dealing with a function or a method?
            if (substr($method, 0, 5) == 'this:')
            {
                // It's a class method - check it exists
                $method = substr($method, 5);
                if (!method_exists($this, $method)) {
                    return new IXR_Error(-32601, 'server error. requested class method "'.$method.'" does not exist.');
                }
                // Call the method
                $result = $this->$method($args);
            }
            else
            {
                // It's a function - does it exist?
                if (!function_exists($method))
                {
                    return new IXR_Error(-32601, 'server error. requested function "'.$method.'" does not exist.');
                }
                // Call the function
                $result = $method($args, $methodname);
            }
            return $result;
        }
    }

    // Load the XML-RPC API's

    foreach($RPC->handlers["library"] as $lib)
    {
        include_once $lib;
    }

    // Set the content type
    header("Content-type: text/xml; charset=utf-8");
    
    // Fire up the server
    $server = new Elgg_Server($RPC->handlers["mapping"]);

    // Serve the request
    $server->serve();

?>
