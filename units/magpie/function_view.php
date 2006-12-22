<?php
global $CFG;
// $parameter = the ID number of the user
    
// Convert $parameter to an integer, see if it exists
$parameter = (int) $parameter;

$feed_offset = optional_param('feed_offset',0,PARAM_INT);

$numposts = count_records_sql('SELECT count(fp.ident) FROM '.$CFG->prefix.'feed_subscriptions fs
                               JOIN '.$CFG->prefix.'feed_posts fp ON fp.feed = fs.feed_id
                               WHERE fs.user_id = ?',array($parameter));

if ($posts = get_records_sql('SELECT fp.*,f.name,f.siteurl,f.tagline,f.url AS feedurl FROM '.$CFG->prefix.'feed_subscriptions fs
                              JOIN '.$CFG->prefix.'feed_posts fp ON fp.feed = fs.feed_id
                              JOIN '.$CFG->prefix.'feeds f ON f.ident = fs.feed_id 
                              WHERE fs.user_id = ? ORDER BY fp.added DESC '.sql_paging_limit($feed_offset,25),
                             array($parameter))) {
    foreach($posts as $post) {
        $run_result .= run("rss:view:post",$post);
        
    }
}

$profile_name = htmlspecialchars(optional_param('profile_name'), ENT_COMPAT, 'utf-8');

if ($numposts - ($feed_offset + 25) > 0) {
    $display_feed_offset = $feed_offset + 25;
    $back = __gettext("Back");
    $run_result .= <<< END
        
        <a href="{$CFG->wwwroot}{$profile_name}/newsclient/all/skip={$display_feed_offset}">&lt;&lt; $back</a>
        
END;
}
if ($feed_offset > 0) {
    $display_feed_offset = $feed_offset - 25;
    if ($display_feed_offset < 0) {
        $display_feed_offset = 0;
    }
    $next = __gettext("Next");
    $run_result .= <<< END
        
        <a href="{$CFG->wwwroot}{$profile_name}/newsclient/all/skip={$display_feed_offset}">$next &gt;&gt;</a>
        
END;
}

?>