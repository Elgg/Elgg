<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $user_id = addslashes($parameter['user_id']);
        $blog_id = addslashes($parameter['blog_id']); 

        $weblog = new Weblog($user_id, $blog_id);
    }

    $run_result = $weblog;

?>
