<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = addslashes($parameter['id']);

        $folder = new Folder($id);
    }
    else
    {
        $folder = new Folder();
    }

    $run_result = $folder;

?>
