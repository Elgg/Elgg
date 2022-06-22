<?php
/**
 * Provide a way of setting your language prefs
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof ElggUser) {
	return;
}

$who_can_change_language = elgg_get_config('who_can_change_language');
if ($who_can_change_language === 'nobody') {
	return;
} elseif ($who_can_change_language === 'admin_only' && !elgg_is_admin_logged_in()) {
	return;
}

$options = elgg()->translator->getInstalledTranslations(elgg_is_admin_logged_in());
$options = array_intersect_key($options, array_flip(elgg()->translator->getAllowedLanguages()));

if (count($options) < 2) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'language',
		'value' => $user->language,
	]);
	
	return;
}

$content = elgg_view_field([
	'#type' => 'select',
	'name' => 'language',
	'value' => $user->language,
	'options_values' => $options,
	'#label' => elgg_echo('user:language:label'),
]);

echo elgg_view_module('info', elgg_echo('user:set:language'), $content);
