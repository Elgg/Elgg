<?php
/**
 * Respond to /admin/plugin_settings requests
 *
 * @since 4.0
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\PageNotFoundException;

// Make sure the 'site' css isn't loaded
elgg_unregister_external_file('css', 'elgg');

elgg_require_js('elgg/admin');

$plugin_id = elgg_extract('plugin_id', $vars);
$plugin = elgg_get_plugin_from_id($plugin_id);
if (!$plugin instanceof ElggPlugin) {
	throw new EntityNotFoundException();
}

if (!elgg_view_exists("plugins/{$plugin_id}/settings")) {
	throw new PageNotFoundException();
}

// build page elements
$title = elgg_echo("admin:plugin_settings") . ': ' . $plugin->getDisplayName();

$content = elgg_view('admin/plugin_settings', [
	'entity' => $plugin,
]);

if (empty($content)) {
	throw new PageNotFoundException(elgg_echo('admin:unknown_section'));
}

// build page
$body = elgg_view_layout('admin', [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'admin/plugin_settings',
	'filter_value' => $plugin_id,
]);

// draw page
echo elgg_view_page($title, $body, 'admin');
