<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = addslashes($parameter['id']);

        $comment = new Comment($id);
    }
    else
    {
        $comment = new Comment();
    }

    $run_result = $comment;

?>
