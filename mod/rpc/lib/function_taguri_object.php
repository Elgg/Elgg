<?php

    /*
     * Tag URI handling
     */

    $run_result = null;

    if (isset($parameters) && $parameters != "")
    {
        // Pickup the tag from the parameters
        $tag = $parameters['tag'];

        // Examine the tag
        $elements = explode(":", $tag);

        // Get the owner
        $user     = explode("@", $elements[1]);
        $username = $user[0];
        $user_id  = run('users:instance', array('user_id' => $username));

        // Get the object type
        $type = $elements[2];

        // Get the object id
        $ident = $elements[3];

        switch ($type)
        {
            case "person":
                $run_result = run('users:instance', array('user_id' => $username));
                break;

            case "community":
                $run_result = run('users:instance', array('user_id' => $username));
                break;

            case "weblog":
                $run_result = run('users:weblog', array('user_id' => $user_id,
                                                        'blog_id' => $ident));
                break;

            case "post":
                $run_result = run('posts:instance', array('id' => $ident));
                break;

            case "comment":
                $run_result = run('comments:instance', array('id' => $ident));
                break;

            case "tag":
                $run_result = run('tags:instance', array('id' => $ident));
                break;

        }
    }
?>
