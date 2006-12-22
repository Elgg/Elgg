<?php

// View a weblog
    
// Get the current profile ID

global $profile_id, $CFG, $db;

// If the weblog offset hasn't been set, it's 0
$weblog_offset = optional_param('weblog_offset',0,PARAM_INT);
$filter = optional_param('filter');

// Get all posts in the system that we can see

$where = run("users:access_level_sql_where",$_SESSION['userid']);
if (empty($filter)) {
    $posts = get_records_select('weblog_posts','('.$where.') AND weblog = '.$profile_id,null,'posted DESC','*',$weblog_offset,'25');
    $numberofposts = count_records_select('weblog_posts','('.$where.') AND weblog = '.$profile_id);
} else {
    $where = str_replace("access","wp.access",$where);
    $where = str_replace("owner","wp.owner",$where);
    $posts = get_records_sql("select * from ".$CFG->prefix."tags t join ".$CFG->prefix."weblog_posts wp on wp.ident = t.ref where ($where) AND t.tagtype = 'weblog' AND wp.weblog = $profile_id AND t.tag = " . $db->qstr($filter) . " order by posted desc limit $weblog_offset,25");
    $numberofposts = get_record_sql("select count(wp.ident) as numberofposts from ".$CFG->prefix."tags t join ".$CFG->prefix."weblog_posts wp on wp.ident = t.ref where ($where) AND t.tagtype = 'weblog' AND wp.weblog = $profile_id AND t.tag = " . $db->qstr($filter));
    $numberofposts = $numberofposts->numberofposts;
}

if (!empty($posts)) {
    
    $lasttime = "";
    
    foreach($posts as $post) {
        
        $time = strftime("%B %d, %Y",$post->posted);
        if ($time != $lasttime) {
            $run_result .= "<h2 class=\"weblog_dateheader\">$time</h2>\n";
            $lasttime = $time;
        }
        
        $run_result .= run("weblogs:posts:view",$post);
        
    }
    
    if (!empty($filter)) {
        $filterlink = "category/".urlencode($filter)."/";
    } else {
        $filterlink = "";
    }
    
    $weblog_name = htmlspecialchars(optional_param('weblog_name'), ENT_COMPAT, 'utf-8');
    
    if ($numberofposts - ($weblog_offset + 25) > 0) {
        $display_weblog_offset = $weblog_offset + 25;
        $back = __gettext("Back");
        $run_result .= <<< END
                
                <a href="{$CFG->wwwroot}{$weblog_name}/weblog/{$filterlink}skip={$display_weblog_offset}">&lt;&lt; $back</a>
                
END;
    }
    if ($weblog_offset > 0) {
        $display_weblog_offset = $weblog_offset - 25;
        if ($display_weblog_offset < 0) {
            $display_weblog_offset = 0;
        }
        $next = __gettext("Next");
        $run_result .= <<< END
                
                <a href="{$CFG->wwwroot}{$weblog_name}/weblog/{$filterlink}skip={$display_weblog_offset}">$next &gt;&gt;</a>
                
END;
    }
    
}

?>