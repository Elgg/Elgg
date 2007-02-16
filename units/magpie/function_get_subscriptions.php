<?php
global $USER;
if (logged_on) {
    
    global $rss_subscriptions;
    
    $parameter = (int) $parameter;
    
    if ($parameter && !isset($rss_subscriptions[$parameter])) {
        $rss_subscriptions[$parameter] = array();
        if ($subscriptions_var = newsclient_get_subscriptions_user($parameter, false)) {
            foreach($subscriptions_var as $subscription) {
                $rss_subscriptions[$parameter][] = $subscription->feed_id;
            }
        }
        
    }
    $run_result = $rss_subscriptions;
}

?>