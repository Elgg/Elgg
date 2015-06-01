<?php
/**
 * Server information
 */

$link = elgg_view('output/url', [
	'is_trusted' => true,
	'href' => 'http://learn.elgg.org/en/latest/guides/services.html#service-env',
	'text' => elgg_echo('more_info'),
]);
$title = elgg_echo('admin:server:label:env')
	. " (" . $link . ")";

echo elgg_view_module('inline', $title, elgg_view('admin/statistics/server/env'));

echo elgg_view_module('inline', elgg_echo('admin:server:label:web_server'), elgg_view('admin/statistics/server/web_server'));

echo elgg_view_module('inline', elgg_echo('admin:server:label:php'), elgg_view('admin/statistics/server/php'));
