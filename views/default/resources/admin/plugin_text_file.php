<?php

elgg_set_context('admin');

$plugin_id = elgg_extract('plugin_id', $vars);
$plugin = elgg_get_plugin_from_id($plugin_id);

$filename = elgg_extract('filename', $vars);

elgg_admin_gatekeeper();

elgg_unregister_css('elgg');
elgg_require_js('elgg/admin');

$error = false;
if (!$plugin) {
	$error = elgg_echo('admin:plugins:markdown:unknown_plugin');
	$body = elgg_view_layout('admin', ['content' => $error, 'title' => $error]);
	echo elgg_view_page($error, $body, 'admin');
	return true;
}

$text_files = $plugin->getAvailableTextFiles();

if (!array_key_exists($filename, $text_files)) {
	$error = elgg_echo('admin:plugins:markdown:unknown_file');
}

$file = $text_files[$filename];
$file_contents = file_get_contents($file);

if (!$file_contents) {
	$error = elgg_echo('admin:plugins:markdown:unknown_file');
}

if ($error) {
	$title = $error;
	$body = elgg_view_layout('admin', ['content' => $error, 'title' => $title]);
	echo elgg_view_page($title, $body, 'admin');
	return true;
}

$title = $plugin->getDisplayName() . ": $filename";

use \Michelf\MarkdownExtra;
$text = MarkdownExtra::defaultTransform($file_contents);

$body = elgg_view_layout('admin', [
	// setting classes here because there's no way to pass classes
	// to the layout
	'content' => '<div class="elgg-markdown">' . $text . '</div>',
	'title' => $title
]);

echo elgg_view_page($title, $body, 'admin');
