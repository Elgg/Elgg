<?php

namespace Elgg\SiteNotifications;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		elgg_register_notification_method('site');

		elgg_register_external_file('js', 'elgg.site_notifications', elgg_get_simplecache_url('site_notifications.js'), 'footer');
	}
}
