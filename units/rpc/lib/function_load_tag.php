<?php
    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = addslashes($parameter['id']);

        $tag = new Tag($id);
    }

    $run_result = $tag;
?>
