<?php
/**
 * Profile friends
 */

$friends = $vars['entity']->listFriends();

if (!$friends) {
	$friends = '<p>' . elgg_echo('profile:no_friends') . '</p>';
}

echo $friends;