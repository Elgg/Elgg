<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = $parameter['id'];

        $comment = new Comment($id);
    }
    else
    {
        $comment = new Comment();
    }

    $run_result = $comment;

?>
