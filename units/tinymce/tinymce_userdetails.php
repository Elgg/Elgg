<?php

    // Get the user preferences for the editor

    // Userid
    $id = (int) $parameter;

    // Editor is enabled by default
    $value = "yes";

    // Query result
    if ($result = user_flag_get('visualeditor', $id)) {
        $value = $result;
    } else {
        // No result, store a default value
        user_flag_set('visualeditor', $value, $id);
    }

    $run_result = $value;
?>
