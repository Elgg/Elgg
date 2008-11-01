<?php

	/**
	 * Elgg Groups latest discussion listing
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
	 
    //get the required variables
    $title = $vars['entity']->title;
    $description = autop($vars['entity']->description);
    $group =  get_entity($vars['entity']->container_guid);
    $forum_created = friendly_time($vars['entity']->time_created);
    if (isloggedin()) {
    $counter = $vars['entity']->countAnnotations("group_topic_post");
    $last_post = $vars['entity']->getAnnotations("group_topic_post", 1, 0, "desc");
    }
                //get the time and user
                foreach($last_post as $last){
                    $last_time = $last->time_created;
                    $last_user = $last->owner_guid;
                }
                
    $info = "<p class=\"latest_discussion_info\">" . elgg_echo('created') . " " . $forum_created . ", " . elgg_echo('with') . " " . $counter . " " . elgg_echo('posts') . "<br /><span class=\"timestamp\">last updated " . friendly_time($last_time);
    if ($u = get_user($last_user)) {
    	$info .= "<br />by <a href=\"" . $u->getURL() . "\">" . $u->username . "</a>";
    }
    $info .= '</span></p>';
	//get the group avatar
	$icon = elgg_view("profile/icon",array('entity' => $group, 'size' => 'small'));
    //get the group and topic title
    if ($group instanceof ElggGroup)
    	$info .= "<p>" . elgg_echo('group') . ": <a href=\"{$group->getURL()}\">{$group->name}</a></p>";
    
	$info .= "<p>" . elgg_echo('topic') . ": <a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$vars['entity']->guid}&group_guid={$group->guid}\">{$title}</a></p>";
	//get the forum description
	$info .= $description;
	
	//display
	echo elgg_view_listing($icon, $info);
		
?>