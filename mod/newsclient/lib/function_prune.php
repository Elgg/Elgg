<?php
//
// Prune the feed_posts table by removing posts older than a certain age.
// Remove any feeds with 0 subscribers.
// Remove any orphaned feed posts.
//

global $CFG;

if ($CFG->rsspostsmaxage) {
    delete_records_select('feed_posts','added < ?',array(time() - (86400 * $CFG->rsspostsmaxage)) );
}

// purge feeds with no subscribers
    execute_sql("DELETE f FROM {$CFG->prefix}feeds f LEFT JOIN {$CFG->prefix}feed_subscriptions fs ON f.ident = fs.feed_id WHERE fs.ident IS NULL;",false);
// purge feed posts for missing feeds
    execute_sql("DELETE fp FROM {$CFG->prefix}feed_posts fp LEFT JOIN {$CFG->prefix}feeds f ON fp.feed = f.ident WHERE f.ident IS NULL;",false);

?>