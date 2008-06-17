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
    
    function lj_getFriends($params, $method)
    {
        // Number of parameters
        $nr_params = 1;
 //return count($params);
        // Do we have the required number of parameters?
        if (count($params) == 0)
        {
            // Raise an XML-RPC error
            return new IXR_Error(-32602, "Invalid method parameters");
        }

        // Parse parameters
        $content = $params;

        $username = $params['username'];

        $user = run('users:instance', array('user_id' => $username));

        if (!$user->exists())
        {
            return new IXR_Error(806, "No such user");

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

        if ($params['friendlimit'])
        {
            $limit = $params['friendlimit'];
        }

        if ($params['includefriendof'] && $params['includefriendof'] == 1)
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

                    $tmp['username']  = $friend->getUserName();
                    $tmp['fullname']  = $friend->getName();
                    $tmp['fgcolor']   = "#FFFFFF";
                    $tmp['bgcolor']   = "#000000";
                    $tmp['groupmask'] = (int) 0; // TODO find out what the bits are about 

                    $friends[] = $tmp;
                }
            }

            $result['friendsof'] = $friends;
        }

        if ($content['includegroups'] && $content['includegroups'] == 1)
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

                $tmp['username']  = $friend->getUserName();
                $tmp['fullname']  = $friend->getName();
                $tmp['fgcolor']   = "#FFFFFF";
                $tmp['bgcolor']   = "#000000";
                $tmp['groupmask'] = (int) 0; // TODO find out what the bits are about 

                $friends[] = $tmp;
            }
        }
        
        $result['friends'] = $friends;

        return $result;
    }
?>
