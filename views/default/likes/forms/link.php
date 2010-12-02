<?php
/**
 * Elgg likes link form - used on riverdashboard where we want the likes link separate from the list of users that liked the object
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {
	$guid = $vars['entity']->getGuid();
	$url = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/likes/add?guid={$guid}");
	//check to see if the user has already liked
	if (!elgg_annotation_exists($guid, 'likes') ) {
		echo "<span class='river_link_divider'> | </span><a class='river_user-like_button link' href=\"{$url}\">" . elgg_echo('likes:likethis') . "</a>";
	}
}