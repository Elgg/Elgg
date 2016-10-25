<?php

$title = elgg_extract('title', $vars);
$intro = elgg_extract('intro', $vars, '');
$content = elgg_extract('content', $vars);
$id = elgg_extract('id', $vars, 'settings_' . str_replace('.', '', microtime(true)));

$header = elgg_view('output/url', [
	'text' => elgg_echo('change'),
	'rel' => 'toggle',
	'href' => "#$id",
	'class' => 'float-alt',
]);
$header .= elgg_format_element('h3', [], $title);

$class = ['hidden'];
if ($intro) {
	$class[] = 'ptm';
}

$content = elgg_format_element('div', [
	'id' => $id,
	'class' => $class,
], $content);

echo elgg_view_module('info', null, $intro . $content, [
	'header' => $header,
]);
