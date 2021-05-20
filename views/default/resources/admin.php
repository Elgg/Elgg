<?php
/**
 * Respond to /admin requests
 *
 * @since 4.0
 */

use Elgg\Exceptions\Http\PageNotFoundException;

// Make sure the 'site' css isn't loaded
elgg_unregister_external_file('css', 'elgg');

elgg_require_js('elgg/admin');

$segments = explode('/', trim(elgg_extract('segments', $vars, ''), '/'));

// default to dashboard
if (!isset($segments[0]) || empty($segments[0])) {
	$segments = ['dashboard'];
}

$title = elgg_echo("admin:{$segments[0]}");
if (count($segments) > 1) {
	$title .= ' : ' . elgg_echo('admin:' .  implode(':', $segments));
}

$view = 'admin/' . implode('/', $segments);
$content = elgg_view($view, [
	'page' => $segments,
]);

if (empty($content)) {
	throw new PageNotFoundException(elgg_echo('admin:unknown_section'));
}

// build page
$body = elgg_view_layout('admin', [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'admin',
]);

// draw page
echo elgg_view_page($title, $body, 'admin');
