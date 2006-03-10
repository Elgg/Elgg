<?php
    /*
     *  Functions for accessing user properties
     */

    // Prepare a list of handlers to be loaded into the XML-RPC server
    $handlers_elgg = array('elgg.user.getUserIcon' => array('function' => 'getUserIcon'));

    // Add the handlers to the global handlers array
    $handlers = $handlers + $handlers_elgg;

    function getUserIcon($params)
    {
        // Number of parameters
        $nr_params = 1;

        // Do we have the required number of parameters?
        if ($params->getNumParams() != $nr_params)
        {
            // Raise an XML-RPC error
            $response = new XML_RPC_Response (0, -32602, "Invalid method parameters");

            return $response;
        }

        // Parse parameters
        $request    = $params->getParam(0);

        $username = $request->scalarval();


        $user = run('users:instance', array('user_id' => $username));

        // Encode the return value
        $value = new XML_RPC_Value($user->getUserIcon());

        // Prepare the response
        $response = new XML_RPC_Response($value);

        return $response;
    }
?>