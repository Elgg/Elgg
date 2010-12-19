<?php
/**
 * Profile friends
 **/

$friends = list_entities_from_relationship('friend', $vars['entity']->getGUID(), FALSE, 'user', '', 0, 10, FALSE);

if(!$friends) {
	$friends = '<p>' . elgg_echo('profile:no_friends') . '</p>';
}

echo $friends;