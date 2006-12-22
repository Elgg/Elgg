<?php

// $parameter = the ID number of the user

// Convert $parameter to an integer, see if it exists
$parameter = (int) $parameter;

// Get all subscriptions
if ($subscriptions = get_records('feed_subscriptions','user_id',$parameter)) {
    run('rss:prune');
    foreach($subscriptions as $subscription) {
        run("rss:update",$subscription->feed_id);
    }
}

?>