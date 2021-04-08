<?php
/**
 * Displays an invalid plugin on the admin screen.
 *
 * An invalid plugin is a plugin whose assertValid() method throws an exception.
 * This usually means there are required files missing, unreadable or in the
 * wrong format.
 */

/* @var ElggPlugin $plugin */
$plugin = elgg_extract('entity', $vars);

$error = elgg_echo('admin:plugins:warning:invalid', [elgg_extract('error', $vars)]);
$error .= ' ' . elgg_echo('admin:plugins:label:location') . ": " . htmlspecialchars($plugin->getPath());

$body = elgg_view_message('error', $error, ['title' => false, 'class' => 'elgg-subtext']);
$body .= elgg_view_message('notice', elgg_echo('admin:plugins:warning:invalid:check_docs'), ['title' => false, 'class' => 'elgg-subtext']);

echo elgg_view('object/elements/summary', [
	'entity' => $plugin,
	'class' => 'elgg-state-draggable elgg-plugin elgg-state-inactive elgg-state-cannot-activate',
	'id' => preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID()),
	'data-guid' => $plugin->guid,
	'icon' => elgg_echo('admin:plugins:cannot_activate'),
	'title' => $plugin->getID(),
	'subtitle' => false,
	'content' => $body,
]);
