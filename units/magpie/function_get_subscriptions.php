<?php
global $USER;
if (logged_on) {
    
    global $rss_subscriptions;
    
    $parameter = (int) $parameter;
    
    if ($parameter && !isset($rss_subscriptions[$parameter])) {
        $rss_subscriptions[$parameter] = array();
        if ($subscriptions_var = get_records('feed_subscriptions', 'user_id', $parameter)) {
            foreach($subscriptions_var as $subscription) {
                $rss_subscriptions[$parameter][] = $subscription->feed_id;
            }
        }
        
    }
    $run_result = $rss_subscriptions;
}

?>