<?php
/**
 * @uses $vars['language']
 */

$language = elgg_extract('language', $vars);

if (empty($language)) {
	// try to detect it
	preg_match("/\/js\/languages\/(.*?).js+/", current_page_url(), $matches);
	
	if (!empty($matches) && isset($matches[1])) {
		$language = $matches[1];
	}	
}

if (empty($language)) {
	// fallback to 'en'
	$language = 'en';
}

$all_translations = elgg_get_config('translations');
$translations = $all_translations['en'];

if ($language != 'en' && !isset($all_translations[$language])) {
	// try to reload missing translations
	reload_all_translations();
	$all_translations = elgg_get_config('translations');
}

if ($language != 'en' && isset($all_translations[$language])) {
	$translations = array_merge($translations, $all_translations[$language]);
}

?>
define(<?php echo json_encode($translations); ?>);
