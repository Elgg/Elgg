<?php

    // TODO: This should almost certainly be a function rather than a run() command

    if (!defined('profileinit')) {
        run("profile:init");
    }

    global $profile_id;
    
    $profile_id = (int) $profile_id;
    
    global $name_cache;
    
    if (empty($parameter)) {
        $user_id = $profile_id;
    } else {
        $user_id = (int) $parameter;
    }
    
    if (!isset($name_cache[$user_id]) || (time() - $name_cache[$user_id]->created > 60)) {

        $name_cache[$user_id]->created = time();
        $name_cache[$user_id]->data = htmlspecialchars(user_info('name',$user_id), ENT_COMPAT, 'utf-8');
        
    }
    $run_result = $name_cache[$user_id]->data;
    
?>