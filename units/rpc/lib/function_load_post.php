<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = addslashes($parameter['id']);

        $post = new Post($id);
    }
    else
    {
        $post = new Post($id);
    }

    $run_result = $post;

?>
