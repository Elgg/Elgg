<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

if (!isset($vars['entity'])
		|| !($vars['entity'] instanceof ElggObject)
		|| ($vars['entity']->getSubtype() !== 'admin_notice')) {
	return;
}

$notice = $vars['entity'];
$message = $notice->description;

global $CONFIG;

$delete = elgg_view('output/url', array(
	'href' => "{$CONFIG->wwwroot}action/admin/delete_admin_notice?guid=$notice->guid",
	'text' => "\xE2\xA8\x89", // &times;
	'is_action' => true,
	'class' => 'elgg-admin-notice',
	'title' => elgg_echo('delete'),
));

echo "<p>$delete$message</p>";
