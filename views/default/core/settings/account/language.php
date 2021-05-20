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

$options = elgg()->translator->getInstalledTranslations(true);
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
