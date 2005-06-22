<?php

    /*
     * LiveJournal API, absurdly complex
     */

    /*
        checkfriends - Checks to see if your friends list has been updated since a specified time.
        consolecommand - Run an administrative command.
        editevent - Edit or delete a user's past journal entry
        editfriendgroups - Edit the user's defined groups of friends.
        editfriends - Add, edit, or delete friends from the user's friends list.
        friendof - Returns a list of which other LiveJournal users list this user as their friend.
        getchallenge - Generate a server challenge string for authentication.
        getdaycounts - This mode retrieves the number of journal entries per day.
        getevents - Download parts of the user's journal.
        getfriends - Returns a list of which other LiveJournal users this user lists as their friend.
        getfriendgroups - Retrieves a list of the user's defined groups of friends.
        login - validate user's password and get base information needed for client to function
        postevent - The most important mode, this is how a user actually submits a new log entry to the server.
        sessionexpire - Expires session cookies.
        sessiongenerate - Generate a session cookie.
        syncitems - Returns a list of all the items that have been created or updated for a user.    
    */

    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers_lj = array('LJ.XMLRPC.getFriends'  => array('function' => 'lj_getFriends'));

    $handlers = $handlers + $handlers_lj;
    
    function lj_getFriends($params)
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

        $content = $request->scalarval();

        $username = $content['username']->scalarval();

        $user = run('users:instance', array('user_id' => $username));

        if (!$user->exists())
        {
            $response = new XML_RPC_Response (0, 806, "No such user");

            return $response;
        }
/*
        if (array_key_exists('auth_method', $request) && $request['auth_method'] != "")
        {
            // Check credentials
            $auth = run('rpc:auth', array("username" => $username,
                                          "password" => $password));

            if ($auth['status'] == true)
            {
            }
            else
            {
            }
        }
*/
        // Always return a struct containing keys, fill according to parameters:
        // - friendsgroup
        // - friendsof
        // - friends

        $result = array();
        $result['friends'] = array();
        $limit = "";

        if ($content['friendlimit'])
        {
            $limit = $content['friendlimit']->scalarval();
        }

        if ($content['includefriendof'] && $content['includefriendof']->scalarval() == 1)
        {
            $result['friendsof'] = array();

            $friends_of = $user->getFriendOf($limit);

            $friends = array();

            // Only if people have linked to this user...
            if(count($friends_of) > 0)
            {
                foreach ($friends_of as $friend_id)
                {
                    $temp = array();

                    $friend = run('users:instance', array('user_id' => $friend_id));

                    $tmp['username']  = new XML_RPC_Value($friend->getUserName());
                    $tmp['fullname']  = new XML_RPC_Value($friend->getName());
                    $tmp['fgcolor']   = new XML_RPC_Value("#FFFFFF");
                    $tmp['bgcolor']   = new XML_RPC_Value("#000000");
                    $tmp['groupmask'] = new XML_RPC_Value(0, 'int'); // TODO find out what the bits are about 

                    $friends[] = new XML_RPC_Value($tmp, 'struct');
                }
            }

            $result['friendsof'] = new XML_RPC_Value($friends, 'array');
        }

        if ($content['includegroups'] && $content['includegroups']->scalarval() == 1)
        {
            $result['friendsgroup'] = array();
        }

        $user_friends = $user->getFriends($limit);

        $friends = array();

        // Only if the user has friends...
        if(count($user_friends) > 0)
        {
            foreach ($user_friends as $friend_id)
            {
                $temp = array();

                $friend = run('users:instance', array('user_id' => $friend_id));

                $tmp['username']  = new XML_RPC_Value($friend->getUserName());
                $tmp['fullname']  = new XML_RPC_Value($friend->getName());
                $tmp['fgcolor']   = new XML_RPC_Value("#FFFFFF");
                $tmp['bgcolor']   = new XML_RPC_Value("#000000");
                $tmp['groupmask'] = new XML_RPC_Value(0, 'int'); // TODO find out what the bits are about 

                $friends[] = new XML_RPC_Value($tmp, 'struct');
            }
        }
        
        $result['friends'] = new XML_RPC_Value($friends, 'array');

        $value = new XML_RPC_Value($result, 'struct');

        $response = new XML_RPC_Response($value);

        return $response;
    }
?>
