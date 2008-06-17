<?php
    /*
     *  Functions for accessing user properties
     */

    function getUserIcon($params, $method)
    {
        // Number of parameters
        $nr_params = 1;

        // Do we have the required number of parameters?
        if (count($params) != $nr_params)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        $username = $params;

        $user = run('users:instance', array('user_id' => $username));

        return $user->getUserIcon();
    }
?>
