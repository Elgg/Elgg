<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $user_id = $parameter['user_id'];
        $blog_id = $parameter['blog_id'];

        $weblog = new Weblog($user_id, $blog_id);
    }

    $run_result = $weblog;

?>
