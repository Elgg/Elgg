<?php

    if($vars['entity']){
        
        foreach($vars['entity'] as $report){
            
            //get the user making the report
            $user = get_user($report->owner_guid)->name;
            $user_url = get_user($report->owner_guid)->getURL();
            
            echo "<div class=\"reported_content\">";
            echo "<p class=\"reported_detail\"><b>" . elgg_echo('reportedcontent:by') . ": </b><a href=\"{$user_url}\">" . $user . "</a>, " . friendly_time($report->time_created) . "</p>";
            echo "<p class=\"reported_detail\"><b>" . elgg_echo('reportedcontent:objecttitle') . ": </b>" . $report->title . "</p>";
            echo "<p class=\"reported_detail\"><b>" . elgg_echo('reportedcontent:objecturl') . ": </b><a href=\"{$report->address}\">" . $report->address . "</a></p>";
            echo "<p class=\"reported_detail\"><b>" . elgg_echo('reportedcontent:reason') . ": </b>" .$report->description . "</p>";
            echo "<p class=\"reported_detail\">[<a href=\"\">" . elgg_echo('reportedcontent:archive') . "</a>] - [<a href=\"\">" . elgg_echo('reportedcontent:delete') . "</a>]</p>";
            echo "</div>";
            
        }
        
    }
    
?>