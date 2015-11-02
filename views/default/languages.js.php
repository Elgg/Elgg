<?php
/**
 * @uses $vars['language']
 */

$language = elgg_extract('language', $vars, 'en');

$all_translations = $GLOBALS['_ELGG']->translations;
$translations = $all_translations['en'];

if ($language != 'en' && !isset($all_translations[$language])) {
	// try to reload missing translations
	reload_all_translations();
	$all_translations = $GLOBALS['_ELGG']->translations;
}

if ($language != 'en' && isset($all_translations[$language])) {
	$translations = array_merge($translations, $all_translations[$language]);
}

?>
define(<?php echo json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
