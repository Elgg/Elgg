<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('user:set:language');
$content = elgg_view_field(array(
	'#type' => 'select',
	'name' => 'language',
	'value' => $user->language,
	'options_values' => get_installed_translations(),
	'#label' => elgg_echo('user:language:label'),
		));

echo elgg_view_module('info', $title, $content);