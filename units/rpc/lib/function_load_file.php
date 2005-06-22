<?php

    // Support run()
    if (isset($parameter) && $parameter != "")
    {
        $id = addslashes($parameter['id']);

        $file = new File($id);
    }
    else
    {
        $file = new File();
    }

    $run_result = $file;

?>
