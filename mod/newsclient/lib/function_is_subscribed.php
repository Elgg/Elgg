<?php
    // parameter is a feed id
    // checks if $page_owner is subscribed to it
    
    $run_result = false;
    
    if (logged_on && isset($parameter)) {
        
        global $page_owner;
        $parameter = (int) $parameter;
        
        global $rss_subscriptions;
        run("rss:subscriptions:get", $page_owner);
        $run_result = in_array($parameter, $rss_subscriptions[$page_owner]);
        
    }
    
?>