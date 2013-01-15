<?php
/**
 * @uses $vars['language']
 */
global $CONFIG;

$language = $vars['language'];

$translations = $CONFIG->translations['en'];

if ($language != 'en') {
	$translations = array_merge($translations, $CONFIG->translations[$language]);
}

echo json_encode($translations);