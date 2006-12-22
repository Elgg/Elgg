<?php

    global $page_owner;
    global $CFG;
    
    if (run("permissions:check", "profile")) {
        
        $editMsg = __gettext("Click here to edit this profile.");

        $run_result .= <<< END
        
        <p>
            <a href="{$CFG->wwwroot}profile/edit.php?profile_id=$page_owner">$editMsg</a>
        </p>
        
END;
        $run_result .= run("profile:edit:link");
            
    }

?>