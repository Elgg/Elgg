<?php

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\PageNotFoundException;

elgg_set_context('admin');
elgg_unregister_external_file('css', 'elgg');

$plugin_id = (string) elgg_extract('plugin_id', $vars);
$plugin = elgg_get_plugin_from_id($plugin_id);
if (!$plugin instanceof \ElggPlugin) {
	throw new EntityNotFoundException(elgg_echo('admin:plugins:markdown:unknown_plugin'));
}

$file_contents = false;
$filename = elgg_extract('filename', $vars);
if (in_array($filename, \ElggPlugin::ADDITIONAL_TEXT_FILES)) {
	$file_contents = file_get_contents($plugin->getPath() . $filename);
}

if (!$file_contents) {
	throw new PageNotFoundException(elgg_echo('admin:plugins:markdown:unknown_file'));
}

$title = $plugin->getDisplayName() . ": $filename";

$body = elgg_view_layout('admin', [
	'content' => elgg_view('admin/plugins/markdown', ['value' => $file_contents]),
	'title' => $title,
	'filter_id' => 'admin/plugin_text_file',
	'filter_value' => "{$plugin_id}/{$filename}",
]);

echo elgg_view_page($title, $body, 'admin');
