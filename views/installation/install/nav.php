<?php
/**
 * Navigation for installation pages
 *
 * @uses $vars['url'] base url of site
 * @uses $vars['next_step'] next step as string
 * @uses $vars['refresh'] should refresh button be shown?
 * @uses $vars['advance'] should the next button be active?
 */

// has a refresh button been requested
$refresh = '';
if (elgg_extract('refresh', $vars)) {
	$refresh = elgg_view('output/url', [
		'text' => elgg_echo('install:refresh'),
		'href' => elgg_get_current_url(),
		'class' => ['elgg-button', 'elgg-button-action'],
	]);
}

// create next button and selectively disable
$next_options = [
	'text' => elgg_echo('install:next'),
	'href' => elgg_get_site_url() . "install.php?step={$vars['next_step']}",
	'class' => ['elgg-button', 'elgg-button-submit'],
];

if (elgg_extract('advance', $vars) === false) {
	// disable the next button
	$next_options['class'][] = 'elgg-state-disabled';
	$next_options['href'] = false;
}

$next = elgg_view('output/url', $next_options);

echo elgg_format_element('div', ['class' => 'elgg-install-nav'], $refresh . $next);
