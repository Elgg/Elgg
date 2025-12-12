<?php
/**
 * Show performance suggestions / warnings
 */

$tabs = [];

$tabs[] = [
	'text' => elgg_echo('admin:performance:label:generic'),
	'content' => elgg_view('admin/performance/generic'),
	'selected' => true,
];

// opcache
if (function_exists('opcache_get_status') && opcache_get_status(false)) {
	$tabs[] = [
		'text' => elgg_echo('admin:server:label:opcache'),
		'content' => elgg_view('admin/server/opcache'),
	];
}

echo elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);
