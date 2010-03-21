<?php
/**
 * Elgg likes add form
 *
 * @package Elgg
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.com/
 *
 * @uses $vars['entity']
 */
	 
if (isset($vars['entity']) && isloggedin()) { 
	$guid = $vars['entity']->getGuid();
	$url = elgg_add_action_tokens_to_url($vars['url'] . "action/likes/add?guid={$guid}");
	//check to see if the user has already liked
	if(!elgg_already_created_annotation($guid, 'likes'))
		echo "<a href=\"{$url}\">" . elgg_echo('likes:likethis') . "</a>";	 	
	//display the number of likes
	$numoflikes = elgg_count_likes($vars['entity']);
	if($numoflikes != 0){
		if($numoflikes == 1)
			echo "<br /><a onclick=\" $('#showLikes').show('slow');\">" . elgg_count_likes($vars['entity']) . " " . elgg_echo('likes:userlikedthis') . "</a>";
		else
			echo "<br /><a onclick=\" $('#showLikes').show('slow');\">" . elgg_count_likes($vars['entity']) . " " . elgg_echo('likes:userslikethis') . "</a>";
	}
	//show the users who liked the object
	echo "<div id=\"showLikes\" style=\"display:none;\">";
	echo list_annotations($vars['entity']->getGUID(),'likes');
	echo "</div>";
}