<?php

// Cycle through all defined profile detail fields and display them

if (!empty($data['profile:details']) && sizeof($data['profile:details']) > 0) {
    
    global $profile_id;
    if ($allvalues = get_records('profile_data','owner',$profile_id)) {
        foreach($data['profile:details'] as $field) {
            $run_result .= run("profile:field:display",array($field, $allvalues));
        }
    }
    
}

?>