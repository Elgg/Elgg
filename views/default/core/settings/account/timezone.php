<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if ($user) {
	$title = elgg_echo('user:set:timezone');
	$content = elgg_echo('user:timezone:label') . ': ';
	$content .= elgg_view("input/dropdown", array(
		'name' => 'timezone',
		'value' => ElggTimezone::getCurrentId($user),
		'options_values' => ElggTimezone::getOptionsValues()
	));
	echo elgg_view_module('info', $title, $content);
}
