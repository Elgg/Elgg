<?php

// Display existing groups

if ($groupdata = run("groups:get", array($_SESSION['userid']))) {
    $header = __gettext("Groups you own"); // gettext variable
    $body = <<< END
        <h5>$header</h5>
END;
                        
    foreach($groupdata as $group) {
        
        $body .= run("groups:edit:display",array($group));
        
    }
    
    $run_result .= $body;
    
}

?>