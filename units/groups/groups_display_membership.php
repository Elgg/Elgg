<?php

// Display existing groups

if ($groupdata = run("groups:get:external", array($_SESSION['userid']))) {
    $header = __gettext("Group membership"); // gettext variable
    $body = <<< END
        <h2>$header</h2>
END;
    
    foreach($groupdata as $group) {
        $body .= templates_draw(array(
                                      'context' => 'databox1',
                                      'name' => $group->name,
                                      'column1' => sprintf(__gettext("Owned by %s"),$group->ownername)
                                      )
                                );
    }
    
    $run_result .= $body;
    
}

?>