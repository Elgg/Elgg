<?php
/**
 * Build a JSON array of the combined language keys to be used in
 * javascript elgg.echo()
 *
 * @uses $vars['language'] the requested language
 */

$language = elgg_extract('language', $vars, 'en');

// requested language
$combine_languages[$language] = true;

// add site language
$site_language = elgg_get_config('language');
if (!empty($site_language)) {
	$combine_languages[$site_language] = true;
}

// add English fallback
$combine_languages['en'] = true;

// fetch all translations
$all_translations = _elgg_services()->translator->getLoadedTranslations();

// make sure all requested languages are loaded
foreach (array_keys($combine_languages) as $language) {
	if (!isset($all_translations[$language])) {
		_elgg_services()->translator->reloadAllTranslations();
		
		$all_translations = _elgg_services()->translator->getLoadedTranslations();
		break;
	}
}

// combine all languages in one result
$translations = [];
foreach (array_keys($combine_languages) as $language) {
	if (!isset($all_translations[$language])) {
		continue;
	}
	$translations = array_merge($all_translations[$language], $translations);
}

?>
define(<?php echo json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
