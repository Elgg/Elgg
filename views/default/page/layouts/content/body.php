<?php
/**
 * Layout body
 *
 * @uses $vars['nav']  		Navigation (override)
 * @uses $vars['header'] 	HTML for overriding the default header (override)
 * @uses $vars['filter'] 	Boolean true if default filter should be used or custom HTML for overriding the default filter (override)
 * @uses $vars['footer'] 	optional footer for main content area
 */

// navigation defaults to breadcrumbs
if (isset($vars['nav'])) {
	echo $vars['nav'];
} else {
	echo elgg_view('navigation/breadcrumbs');
}

// allow page handlers to override the default header
if (isset($vars['header'])) {
	$vars['header_override'] = $vars['header'];
}
echo elgg_view('page/layouts/content/header', $vars);

// allow page handlers to override the default filter
if (!empty($vars['filter'])) {
	if ($vars['filter'] !== true) {
		// filter is custom html (override)
		$vars['filter_override'] = $vars['filter'];
	}
	echo elgg_view('page/layouts/content/filter', $vars);
}

echo $vars['content'];

if (isset($vars['area1'])) {
	// @deprecated 1.8
	elgg_deprecated_notice("Use content instead of area1", 1.8);
	echo $vars['area1'];
}

// optional footer for main content area
$params = $vars;
$params['content'] = elgg_extract('footer', $vars, '');
echo elgg_view('page/layouts/content/footer', $params);