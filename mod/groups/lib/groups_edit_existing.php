<?php

// Display existing groups

if ($groupdata = run("groups:get", array($_SESSION['userid']))) {
    
    $description = __gettext("To add friends to your access controls, select their names from the list on the left and click the 'add selected' button. To remove friends from each control, select their names from the list on the right and click the 'remove selected' button.");
    
    $header = __gettext("Access controls you own"); // gettext variable
    $body = <<< END
        <h5>{$header}</h5>
        <p>{$description}</p>
END;
                        
    foreach($groupdata as $group) {
        
        $body .= run("groups:edit:display",array($group));
        
    }
    
    $run_result .= $body;
    
}

?>