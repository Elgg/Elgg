<?php
/**
 * Displays an invalid plugin on the admin screen.
 *
 * An invalid plugin is a plugin whose isValid() method returns false.
 * This usually means there are required files missing, unreadable or in the
 * wrong format.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */
/* @var ElggPlugin $plugin */
$plugin = elgg_extract('entity', $vars);

$plugin_id = $plugin->getID();

$body = "<div class='elgg-plugin-title'>{$plugin_id}</div>";

$error = elgg_echo('admin:plugins:warning:invalid', [$plugin->getError()]);
$error .= elgg_echo('admin:plugins:label:location') . ": " . htmlspecialchars($plugin->getPath());

$message = elgg_format_element('p', [
	'class' => 'elgg-text-help elgg-state-error',
], $error);
$message .= elgg_format_element('p', [
	'class' => 'elgg-text-help',
], elgg_echo('admin:plugins:warning:invalid:check_docs'));

$body .= "<div>$message</div>";

$result = elgg_view_image_block(elgg_echo('admin:plugins:cannot_activate'), $body);
echo elgg_format_element('div', [
	'class' => 'elgg-state-draggable elgg-plugin elgg-state-inactive elgg-state-cannot-activate',
	'id' => preg_replace('/[^a-z0-9-]/i', '-', $plugin_id),
	'data-guid' => $plugin->guid,
], $result);
