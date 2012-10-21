<?php
/**
 * @uses $vars['language']
 */
global $CONFIG;

$language = elgg_extract('language', $vars);

if (!$language) {
	$language = get_input('language', 'en');
}

$translations = $CONFIG->translations['en'];

if ($language != 'en') {
	$translations = array_merge($translations, $CONFIG->translations[$language]);
}

echo json_encode($translations);
