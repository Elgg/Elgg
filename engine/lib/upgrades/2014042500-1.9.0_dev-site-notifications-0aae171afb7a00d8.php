<?php
/**
 * Elgg 1.9.0-dev upgrade 2014042500
 * site-notifications
 *
 * When upgrading from Elgg 1.8.x to Elgg 1.9 and you have the messages plugin enabled
 * also enable the site_notifications plugin as it takes over the 'site' notification part
 */

// is the messages plugin enabled
if (elgg_is_active_plugin('messages')) {
	// get the site_notifications plugin
	$site_notifications = elgg_get_plugin_from_id('site_notifications');
	
	if (!empty($site_notifications)) {
		$site_notifications->activate();
	}
}
