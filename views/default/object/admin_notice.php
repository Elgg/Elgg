<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

if (isset($vars['entity']) && elgg_instanceof($vars['entity'], 'object', 'admin_notice')) {
	$notice = $vars['entity'];
	$message = $notice->description;
	echo "<p>$message</p>";
}

