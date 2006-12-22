<?php

// View a weblog

// Get the current profile ID

global $page_owner;
global $CFG;

// If the weblog offset hasn't been set, it's 0
$weblog_offset = optional_param('weblog_offset',0,PARAM_INT);

// Get all posts in the system by our friends that we can see

$friends = run("friends:get",array($page_owner));

$where2 = "weblog IN (" . $page_owner;
        
if (!empty($friends)) {
    foreach($friends as $friend) {
        if ($where2) {
            $where2 .= ", ";
        }
        $where2 .= $friend->user_id;
    }
}
$where2 .= ")";

$where1 = run("users:access_level_sql_where",$_SESSION['userid']);
// if (!isset($_SESSION['friends_posts_cache']) || (time() - $_SESSION['friends_posts_cache']->created > 60)) {
// $_SESSION['friends_posts_cache']->created = time();
// $_SESSION['friends_posts_cache']->data = get_records_select('weblog_posts','('.$where1.') AND ('.$where2.')',null,'posted DESC','*',$weblog_offset,25);
// }
// $posts = $_SESSION['friends_posts_cache']->data;
$posts = get_records_select('weblog_posts','('.$where1.') AND ('.$where2.')',null,'posted DESC','*',$weblog_offset,25);
$numberofposts = count_records_select('weblog_posts','('.$where1.') AND ('.$where2.')');
if (!empty($posts)) {
    
    $lasttime = "";
    
    foreach($posts as $post) {
        
        $time = gmstrftime("%B %d, %Y",$post->posted);
        if ($time != $lasttime) {
            $run_result .= "<h2 class=\"weblogdateheader\">$time</h2>\n";
            $lasttime = $time;
        }
        
        $run_result .= run("weblogs:posts:view",$post);
        
    }
    
    $weblog_name = htmlspecialchars(optional_param('weblog_name'), ENT_COMPAT, 'utf-8');
    
    if ($numberofposts - ($weblog_offset + 25) > 0) {
        $display_weblog_offset = $weblog_offset + 25;
        $back = __gettext("Back"); // gettext variable
        $run_result .= <<< END
            
                <a href="{$CFG->wwwroot}{$weblog_name}/weblog/friends/skip={$display_weblog_offset}">&lt;&lt; $back</a>
                <!-- <form action="" method="post" style="display:inline">
                    <input type="submit" value="&lt;&lt; Previous 25" />
                    <input type="hidden" name="weblog_offset" value="{$display_weblog_offset}" />
                </form> -->
                
END;
    }
    if ($weblog_offset > 0) {
        $display_weblog_offset = $weblog_offset - 25;
        if ($display_weblog_offset < 0) {
            $display_weblog_offset = 0;
        }
        $next = __gettext("Next"); // gettext variable
        $run_result .= <<< END
                
                <a href="{$CFG->wwwroot}{$weblog_name}/weblog/friends/skip={$display_weblog_offset}">$next &gt;&gt;</a>
                
END;
    }
    
}

?>