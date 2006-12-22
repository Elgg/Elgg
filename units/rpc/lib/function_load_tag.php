<?php
    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = $parameter['id'];

        $tag = new Tag($id);
    }

    $run_result = $tag;
?>
