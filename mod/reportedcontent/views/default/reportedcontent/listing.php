<?php

    if($vars['entity']){
        
        foreach($vars['entity'] as $report){
            
            //get the user making the report
            $user = get_user($report->owner_guid)->name;
            $user_url = get_user($report->owner_guid)->getURL();
            
            //find out if the report is current or archive
	        if($report->state == 'archived'){
		        $reportedcontent_background = "archive";
	        }else{
    	        $reportedcontent_background = "active";
	        }
		   

            echo "<div class=\"reportedcontent_content {$reportedcontent_background}\">";
            echo "<p class=\"reportedcontent_detail\"><b>" . elgg_echo('reportedcontent:by') . ": </b><a href=\"{$user_url}\">" . $user . "</a>, " . friendly_time($report->time_created) . "</p>";
            echo "<p class=\"reportedcontent_detail\"><b>" . elgg_echo('reportedcontent:objecttitle') . ": </b>" . $report->title . "</p>";
	        echo "<p class=\"reportedcontent_detail\">[<a href=\"" . $vars['url'] . "action/reportedcontent/archive?item=" . $report->guid . "\">" . elgg_echo('reportedcontent:archive') . "</a>] - [<a href=\"" . $vars['url'] . "action/reportedcontent/delete?item=" . $report->guid . "\" onclick=\"return confirm('" . elgg_echo('reportedcontent:areyousure') . "')\">" . elgg_echo('reportedcontent:delete') . "</a>]</p>";
	        echo "<p><a class=\"manifest_details\">" . elgg_echo("more info") . "</a></p>";
	        echo "<div class=\"manifest_file\">";
            echo "<p class=\"reportedcontent_detail\"><b>" . elgg_echo('reportedcontent:objecturl') . ": </b><a href=\"{$report->address}\">" . elgg_echo('reportedcontent:visit')  . "</a></p>";
            echo "<p class=\"reportedcontent_detail\"><b>" . elgg_echo('reportedcontent:reason') . ": </b>" .$report->description . "</p>";
            echo "</div></div>";
            
        }
        
    }
    
?>