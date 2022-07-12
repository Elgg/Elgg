<?php
/**
 * Holds helper functions for developers plugin
 */

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
	
	if (elgg_stristr(elgg_get_current_url(), elgg_normalize_url('cache/'))) {
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
