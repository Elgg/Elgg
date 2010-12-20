<?php
/**
 * Profile groups
 */

$options = array(
	'relationship_guid' => $vars['entity']->getGUID(),
	'relationship' => 'member',
	'inverse_relationship' => false,
	'full_view' => false,
);
$groups = elgg_list_entities_from_relationship($options);

if (!$groups) {
	$groups = '<p>' . elgg_echo('profile:no_groups') . '</p>';
}

echo $groups;