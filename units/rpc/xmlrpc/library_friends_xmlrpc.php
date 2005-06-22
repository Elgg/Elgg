<?php

    /*
     *  Functions related to friends
     */
     
    // TODO: implement error handling

    include path . "units/rpc/lib/library_friends.php";

    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers_foaf = array('friends.getFriends'   => array('function' => 'getFriends'),
                           'friends.getFamily'    => array('function' => 'getFamily'),
                           'friends.addFriend'    => array('function' => 'addFriend'),
                           'friends.removeFriend' => array('function' => 'removeFriend'),
                           'friends.test'  => array('function' => 'test'));

    // Add the handlers to the global handlers array
    $handlers = $handlers + $handlers_foaf;

    // Return all friends of a user via an associative array, 
    // where key is the user_id and value is the username

    function getFriends($params)
    {
        $param = $params->getParam(0);
        $user  = $param->scalarval();

        $friends = array();
        $result  = getElggFriends($user);

        if (sizeof($result) > 0)
        {
            foreach($result as $row)
            {
                $friends[$row->user_id] = new XML_RPC_Value($row->name);
            }
        }
        
        $value = new XML_RPC_Value($friends, 'struct');

        $response = new XML_RPC_Response($value);

        return $response;
    }

    // Return all relations, nice for full data analysis

    function getFamily($params)
    {
    
    }
    
    // Add a friend to user's friends list
    
    function addFriend($params)
    {
        $user   = $params->getParam(0);
        $friend = $params->getParam(1);

        //if ( $_SERVER['PHP_AUTH_USER'] == $user)
        
        // use validate()

    }
    
    // Remove a foe from user's friends list
    
    function removeFriend($params)
    {
        $user = $params->getParam(0);
        $foe  = $params->getParam(1);

        //if ( $_SERVER['PHP_AUTH_USER'] == $user)

        // use validate()
        
    }
    
    function test($params)
    {
        $val = new XML_RPC_Value($messages, 'string');
        return validate($val);    
    }
?>
