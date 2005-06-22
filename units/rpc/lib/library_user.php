<?php

    /*
     *  Functions related to users
     */

    include path . "units/rpc/libelgg/library.php";
    
    // Prepare a list of handlers to be loaded into the XML-RPC server

    $handlers_user = array('user.getFriends'    => array('function' => 'getFriends'),
                           'user.getAllFriends' => array('function' => 'getAllFriends'),
                           'user.addFriend'     => array('function' => 'addFriend'),
                           'user.removeFriend'  => array('function' => 'removeFriend'),
                           'user.test'  => array('function' => 'test'));

    function getUserNameById($params)
    {
    }
    
    function getNameById($params)
    {
    }
    
    function getEmailAddress($params)
    {
    }
    
    function setEmailAddress($params)
    {
    }

    function getNick($params)
    {
    }

    function setNick($params)
    {
    }

?>
