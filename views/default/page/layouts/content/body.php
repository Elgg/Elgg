<?php
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

// @deprecated 1.8
if (isset($vars['area1'])) {
	// @todo: add deprecated notice
	echo $vars['area1'];
}

// optional footer for main content area
$params = $vars;
$params['content'] = elgg_extract('footer', $vars, '');
echo elgg_view('page/layouts/content/footer', $params);