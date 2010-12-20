<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

echo elgg_view('profile/status', array("entity" => $vars['entity']));

$params = array(
	'subject_guid' => $vars['entity']->guid,
	'limit' => 5,
);
echo elgg_list_river($params);
