<?php
global $CFG;
// $parameter = the ID number of the feed
    
// Convert $parameter to an integer, see if it exists
$parameter = (int) $parameter;

// If the feed offset hasn't been set, it's 0
$feed_offset = optional_param('feed_offset',0,PARAM_INT);

$numposts = count_records_sql('SELECT COUNT(fp.ident) FROM '.$CFG->prefix.'feed_posts fp
                               JOIN '.$CFG->prefix.'feeds f ON f.ident = fp.feed
                               WHERE f.ident = ?',array($parameter));
if ($posts = get_records_sql('SELECT fp.*,f.name,f.siteurl,f.tagline FROM '.$CFG->prefix.'feed_posts fp
                              JOIN '.$CFG->prefix.'feeds f ON f.ident = fp.feed
                              WHERE f.ident = ? ORDER BY fp.added DESC, fp.ident ASC '
                             .sql_paging_limit($feed_offset,'25'),array($parameter))) {

    foreach($posts as $post) {
        $run_result .= run("rss:view:post",$post);
    }
}

if ($numposts - ($feed_offset + 25) > 0) {
    $display_feed_offset = $feed_offset + 25;
    $back = __gettext("Back");
    $run_result .= <<< END
        
                <a href="{$CFG->wwwroot}_rss/individual.php?feed={$parameter}&amp;feed_offset={$display_feed_offset}">&lt;&lt; $back</a>
                
END;
}
if ($feed_offset > 0) {
    $display_feed_offset = $feed_offset - 25;
    if ($display_feed_offset < 0) {
        $display_feed_offset = 0;
    }
    $next = __gettext("Next");
    $run_result .= <<< END
                
                <a href="{$CFG->wwwroot}_rss/individual.php?feed={$parameter}&amp;feed_offset={$display_feed_offset}">$next &gt;&gt;</a>
                
END;
}
        
?>