<?php

$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);

$filename = get_input('filename');

elgg_admin_gatekeeper();
_elgg_admin_add_plugin_settings_menu();
elgg_set_context('admin');

elgg_unregister_css('elgg');
elgg_load_js('elgg.admin');
elgg_load_js('jquery.jeditable');
elgg_load_library('elgg:markdown');

$error = false;
if (!$plugin) {
	$error = elgg_echo('admin:plugins:markdown:unknown_plugin');
	$body = elgg_view_layout('admin', array('content' => $error, 'title' => $error));
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
	$body = elgg_view_layout('admin', array('content' => $error, 'title' => $title));
	echo elgg_view_page($title, $body, 'admin');
	return true;
}

$title = $plugin->getManifest()->getName() . ": $filename";
$text = Markdown($file_contents);

$body = elgg_view_layout('admin', array(
	// setting classes here because there's no way to pass classes
	// to the layout
	'content' => '<div class="elgg-markdown">' . $text . '</div>',
	'title' => $title
));

echo elgg_view_page($title, $body, 'admin');