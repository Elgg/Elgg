<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

if (isset($vars['entity']) && elgg_instanceof($vars['entity'], 'object', 'admin_notice')) {
	$notice = $vars['entity'];
	$message = $notice->description;

	$delete = elgg_view('output/url', array(
		'href' => "action/admin/delete_admin_notice?guid=$notice->guid",
		'text' => elgg_view_icon('delete'),
		'is_action' => true,
		'class' => 'elgg-admin-notice',
		'is_trusted' => true,
	));

	echo "<p>$delete$message</p>";
}

