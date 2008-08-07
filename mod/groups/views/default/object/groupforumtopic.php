<?php

	/**
	 * Elgg Forum listing
	 * 
	 * @package ElggForums
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Dave Tosh <dave@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
	 
    //get the required variables
    $title = $vars['entity']->title;
    $description = autop($vars['entity']->description);
    $group =  get_entity($vars['entity']->container_guid);
    $num_topics = $vars['entity']->countEntitiesFromRelationship("forum_topic");
    $forum_created = friendly_time($vars['entity']->time_created);
    // get the topic entities 
    $entities = $vars['entity']->getEntitiesFromRelationship("forum_topic");
    $counter = 0; 
    foreach($entities as $ent){
        //count the post annotations for each topic
        $counter = $ent->countAnnotations("topic_post");
    }
	 
    $info = "<p style=\"float:right;\">" . elgg_echo('created') . " " . $forum_created . ", " . elgg_echo('with') . " " . $num_topics . " " . elgg_echo('topics') . " " . elgg_echo('and') . " " . $counter . " " . elgg_echo('posts') . ".</p>";
	//get the group avatar
	$icon = elgg_view("profile/icon",array('entity' => $group, 'size' => 'tiny'));
    //get the group and topic title
    $info .= "<p>" . elgg_echo('group') . ": <a href=\"{$group->getURL()}\">{$group->name}</a></p>";
    
	$info .= "<p>" . elgg_echo('groups:topic') . ": <a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$vars['entity']->guid}&group_guid={$group->guid}\">{$title}</a></p>";
	//get the forum description
	$info .= $description;
	
	//display
	echo elgg_view_listing($icon, $info);
		
?>