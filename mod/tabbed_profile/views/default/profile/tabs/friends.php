<?php
/**
 * Profile friends
 */

$options = array(
	'relationship_guid' => $vars['entity']->getGUID(),
	'relationship' => 'friend',
	'inverse_relationship' => false,
	'full_view' => false,
);
$friends = elgg_list_entities_from_relationship($options);

if (!$friends) {
	$friends = '<p>' . elgg_echo('profile:no_friends') . '</p>';
}

echo $friends;