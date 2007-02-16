<?php

// $parameter = the ID number of the user
global $CFG;

// Convert $parameter to an integer, see if it exists
$parameter = (int) $parameter;

// Get all subscriptions
if ($subscriptions = get_records('feed_subscriptions','user_id',$parameter)) {
    if (empty($CFG->newsclient_lastcronprune)) {
        set_config('newsclient_lastcronprune',time());
    }
    if ((time() - 86400) >= $CFG->newsclient_lastcronprune) {
        run('rss:prune');
        set_config('newsclient_lastcronprune',time());
    }
    foreach($subscriptions as $subscription) {
        run("rss:update",$subscription->feed_id);
    }
}

?>