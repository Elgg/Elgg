<?php

elgg_set_context('admin');

$plugin_id = elgg_extract('plugin_id', $vars);
$plugin = elgg_get_plugin_from_id($plugin_id);

$filename = elgg_extract('filename', $vars);

elgg_unregister_external_file('css', 'elgg');
elgg_require_js('elgg/admin');

if (!$plugin) {
	$error = elgg_echo('admin:plugins:markdown:unknown_plugin');
	$body = elgg_view_layout('admin', [
		'content' => $error,
		'title' => $error,
	]);
	echo elgg_view_page($error, $body, 'admin');
	return true;
}

$file_contents = false;
if (in_array($filename, \ElggPlugin::ADDITIONAL_TEXT_FILES)) {
	$file_contents = file_get_contents($plugin->getPath() . $filename);
}

if (!$file_contents) {
	$error = elgg_echo('admin:plugins:markdown:unknown_file');
	$body = elgg_view_layout('admin', ['content' => $error, 'title' => $error]);
	echo elgg_view_page($error, $body, 'admin');
	return true;
}

$title = $plugin->getDisplayName() . ": $filename";

use \Michelf\MarkdownExtra;
$text = MarkdownExtra::defaultTransform($file_contents);

$body = elgg_view_layout('admin', [
	// setting classes here because there's no way to pass classes
	// to the layout
	'content' => elgg_format_element('div', ['class' => 'elgg-markdown'], $text),
	'title' => $title,
	'filter_id' => 'admin/plugin_text_file',
	'filter_value' => "{$plugin_id}/{$filename}",
]);

echo elgg_view_page($title, $body, 'admin');
