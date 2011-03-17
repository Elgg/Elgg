<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

if (isset($vars['entity']) && elgg_instanceof($vars['entity'], 'object', 'admin_notice')) {
	$notice = $vars['entity'];
	$message = $notice->description;

	$delete = elgg_view('output/url', array(
		'href' => "action/admin/delete_admin_notice?guid=$notice->guid",
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
		'is_action' => true,
		'class' => 'elgg-admin-notice'
	));

	echo "<p>$delete$message</p>";
}

