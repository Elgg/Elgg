<?php
/**
 * Elgg report content listing
 * 
 * @package ElggReportContent
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

if($vars['entity']){
	$id = 0;
    foreach($vars['entity'] as $report){
	    
	    // increment our id counter
	    $id++;
        
        //get the user making the report
        $user = get_user($report->owner_guid)->name;
        $user_url = get_user($report->owner_guid)->getURL();
        
        //find out if the report is current or archive
        if($report->state == 'archived'){
	        $reportedcontent_background = "archived_report";
        }else{
	        $reportedcontent_background = "active_report";
        }
	   
        echo "<div class='admin_settings reported_content {$reportedcontent_background}'>";
        echo "<div class='clearfloat controls'>";
        if($report->state != 'archived')
        	  echo "<a class='action_button' href=\"" . elgg_add_action_tokens_to_url($vars['url'] . "action/reportedcontent/archive?item={$report->guid}") . "\">" . elgg_echo('reportedcontent:archive') . "</a>";
        echo "<a class='action_button disabled' href=\"" . elgg_add_action_tokens_to_url($vars['url'] . "action/reportedcontent/delete?item={$report->guid}") . "\" onclick=\"return confirm('" . elgg_echo('reportedcontent:areyousure') . "')\">" . elgg_echo('reportedcontent:delete') . "</a></div>";
        echo "<p><b>" . elgg_echo('reportedcontent:by') . ": </b><a href=\"{$user_url}\">" . $user . "</a>, " . friendly_time($report->time_created) . "</p>";
        echo "<p><b>" . elgg_echo('reportedcontent:objecttitle') . ": </b>" . $report->title;
		echo "<br /><a onclick=\"elgg_slide_toggle(this,'.reported_content','.container{$id}');\" class='details_link'>" . elgg_echo('reportedcontent:moreinfo') . "</a></p>";
        echo "<div class='details container{$id} hidden'>";
        echo "<p><b>" . elgg_echo('reportedcontent:objecturl') . ": </b><a href=\"{$report->address}\">" . elgg_echo('reportedcontent:visit')  . "</a></p>";
        echo "<p><b>" . elgg_echo('reportedcontent:reason') . ": </b>" .$report->description . "</p>";
        echo "</div></div>";
    }
    
} else {
	echo "<p class='margin_top'>".elgg_echo('reportedcontent:none')."</p>";
}