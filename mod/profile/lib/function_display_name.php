<?php

    // TODO: remove all references to this that aren't a direct reference to the function
    
    if (empty($parameter)) {
        global $profile_id;
        $profile_id = (int) $profile_id;
        $user_id = $profile_id;
    } else {
        $user_id = (int) $parameter;
    }
    
    $run_result = user_name($user_id);
    
?>