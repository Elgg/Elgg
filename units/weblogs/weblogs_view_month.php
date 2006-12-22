<?php

// View a weblog's posts for a particular month

// Get the current profile ID

global $profile_id;

// If the months haven't been set, they're the current months
$month = optional_param('month',gmdate('m'),PARAM_INT);

// If the years haven't been set, they're the current years
$year = optional_param('year',gmdate('y'),PARAM_INT);

// Get all posts in the system that we can see

$where = run("users:access_level_sql_where",$_SESSION['userid']);

$posts = get_records_select('weblog_posts','('.$where.') AND weblog = '.$profile_id ."
                             AND posted >= ".gmmktime(0,0,0,$month,1,$year)."
                             AND posted < ".gmmktime(0,0,0,($month + 1), 1, $year),
                            null,'posted ASC');

if (!empty($posts)) {
    
    $lasttime = "";
    
    $run_result .= "<h1 class=\"weblogdateheader\">" . strftime("%B %Y", gmmktime(0,0,0,$month,1,$year)) . "</h1>\n";
    
    foreach($posts as $post) {
        
        $time = strftime("%B %d, %Y", $post->posted);
        if ($time != $lasttime) {
            $run_result .= "<h2 class=\"weblogdateheader\">$time</h2>\n";
            $lasttime = $time;
        }
        
        $run_result .= run("weblogs:posts:view",$post);
        
    }
    
}

?>