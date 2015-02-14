<?php
/**
 * Group status for logged in user
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

$user = elgg_get_logged_in_user_entity();
if (!$user) {
	return;
}

$subscribed = elgg_extract('subscribed', $vars);

$body = elgg_view_menu('groups:my_status', array(
	'entity' => $group,
	'user' => $user,
	'subscribed' => $subscribed,
));

if($body) {
	echo elgg_view_module('aside', elgg_echo('groups:my_status'), $body);
}
