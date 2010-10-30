<?php
/**
 * Elgg profile icon avatar menu: Add / Remove friend links
 * 
 * @package ElggProfile
 * 
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
 */
$ts = time();
$token = generate_action_token($ts);
if ($vars['entity']->isFriend()) {
	echo elgg_view('output/confirmlink', array(
		'href' => elgg_get_site_url()."action/friends/remove?friend={$vars['entity']->getGUID()}",
		'text' => elgg_echo('friend:remove'),
		'class' => 'remove_friend'
	));
} else {
	echo elgg_view('output/confirmlink', array(
		'href' => elgg_get_site_url()."action/friends/add?friend={$vars['entity']->getGUID()}",
		'text' => elgg_echo('friend:add'),
		'class' => 'add_friend'
	));
}