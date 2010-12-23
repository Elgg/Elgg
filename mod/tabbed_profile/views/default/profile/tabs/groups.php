<?php
/**
 * Profile groups
 */

$groups = $vars['entity']->listGroups();

if (!$groups) {
	$groups = '<p>' . elgg_echo('profile:no_groups') . '</p>';
}

echo $groups;