<?php

    /*
     *  Functions related to friends
     */



    // All friends of an elgg user, returns a query result construct
   
    function getElggFriends($ident)
    {
        //$ident = userNameToId($user);
        
        $result = db_query("select friends.friend as user_id,
                            users.name from friends 
                            left join users on users.ident = friends.friend 
                            where friends.owner = '$ident'");

        return $result;
    }

    // Return all relations, nice for full data analysis

    function getElggFamily()
    {
        // Get a list of all users, including friends (new query)
        
        // Get a list of all users and call getElggFriends for each (expensive),
    
    }
    
    // Add a friend to user's friends list
    
    function addElggFriend($user, $friend)
    {
        // Check identification and authorization, else throw exception
    }
    
    // Remove a foe from user's friends list
    
    function removeElggFriend($user, $foe)
    {
        // Check identification and authorization, else throw exception
    }
        
?>
