<?php

// $parameter = the ID number of the user

// Convert $parameter to an integer, see if it exists
$parameter = (int) $parameter;

// Get all subscriptions
if ($subscriptions = get_records_select('feeds','last_updated <= ',array(time() - 300))) {
    foreach($subscriptions as $subscription) {
        run("rss:update",$subscription->ident);
        echo stripslashes($subscription->url) . " checked (last updated ".date("l dS \of F Y h:i:s A",$subscription->last_updated).")<br />";
    }
}

?>