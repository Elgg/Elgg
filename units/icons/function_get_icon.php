<?php

    // TODO: This should almost certainly be a function rather than a run() command

    if (!empty($parameter)) {
        
        //global $icon_cache; -- user_info is cached, so this is redundant
        $user_id = (int) $parameter;
        $run_result = user_info('icon', $user_id);
        
    }

?>