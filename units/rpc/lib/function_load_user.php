<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $user_id = addslashes($parameter['user_id']);

        $user = new User($user_id);
    }

    $run_result = $user;
?>
