<?php
/**
 * Holds helper functions for developers plugin
 */

/**
 * Adds debug info to all translatable strings
 *
 * @return void
 */
function developers_decorate_all_translations() {
	$language = get_current_language();
	_developers_decorate_translations($language);
	_developers_decorate_translations('en');
}

/**
 * Appends " ($key)" to all strings for the given language.
 *
 * This function checks if the suffix has already been added so it is idempotent
 *
 * @param string $language Language code like "en"
 *
 * @return void
 *
 * @internal
 */
function _developers_decorate_translations($language) {
	$translations = _elgg_services()->translator->getLoadedTranslations();

	foreach ($translations[$language] as $key => &$value) {
		$needle = " ($key)";
		
		// if $value doesn't already end with " ($key)", append it
		if (substr($value, -strlen($needle)) !== $needle) {
			$value .= $needle;
		}
	}
}

/**
 * Post-process a view to add wrapper comments to it
 *
 * 1. Only process views served with the 'default' viewtype.
 * 2. Does not wrap views that are not HTML.
 * 3. Does not wrap input and output views.
 * 4. Does not wrap html head or the primary page shells
 *
 * @warning this will break views in the default viewtype that return non-HTML data
 * that do not match the above restrictions.
 *
 * @param \Elgg\Hook $hook 'view', 'all'
 *
 * @return void|string
 */
function developers_wrap_views(\Elgg\Hook $hook) {
	$result = $hook->getValue();
	if (elgg_get_viewtype() !== 'default' || elgg_is_empty($result)) {
		return;
	}
	
	if (elgg_stristr(current_page_url(), elgg_normalize_url('cache/'))) {
		return;
	}
	
	$excluded_views = [
		'page/default',
		'page/admin',
		'page/elements/head',
		'page/elements/html',
	];

	$view = $hook->getParam('view');
	if (in_array($view, $excluded_views)) {
		return;
	}
	
	$excluded_bases = [
		'resources',
		'input', // because of possible html encoding in views, which would result in debug cobe being shown to users
		'output', // because of possible html encoding in views, which would result in debug cobe being shown to users
		'embed',
		'icon',
		'json',
		'xml',
	];
	
	$view_hierarchy = explode('/', $view);
	if (in_array($view_hierarchy[0], $excluded_bases)) {
		return;
	}
	
	if ((new \SplFileInfo($view))->getExtension()) {
		// don't wrap views with extension
		// for example: elements/buttons.css
		return;
	}
	
	$view_location = _elgg_services()->views->findViewFile($view, 'default');
	$project_path = str_replace('\\', '/', Elgg\Project\Paths::project()); // handle Windows paths
	$view_location = str_ireplace($project_path, '', $view_location); // strip project path from view location
	
	return "<!-- developers:begin {$view} @ {$view_location} -->{$result}<!-- developers:end {$view} -->";
}
