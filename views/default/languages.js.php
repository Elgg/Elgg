<?php
/**
 * @uses $vars['language']
 * @uses $vars['timing'] If "early", return only keys marked as critical
 *                       If "late", return the rest of the language keys
 *                       If missing, return all keys (2.0 BC)
 */

$language = elgg_extract('language', $vars, 'en');
$timing = elgg_extract('timing', $vars);

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

if ($timing) {
	$early = [];
	$late = $translations;

	// avoid copy-on-write when moving elements
	unset($translations);

	foreach (_elgg_services()->translator->getEarlyKeys() as $key) {
		if (isset($late[$key])) {
			$early[$key] = $late[$key];
			unset($late[$key]);
		}
	}

	$translations = ($timing === 'early') ? $early : $late;
}

?>
define(<?php echo json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
