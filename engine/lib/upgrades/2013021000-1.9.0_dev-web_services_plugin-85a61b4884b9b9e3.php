<?php
/**
 * Elgg 1.9.0-dev upgrade 2013021000
 * web_services_plugin
 *
 * Enables the web services plugin if web services are turned on
 */

if (!get_config('disable_api')) {
	$plugin = elgg_get_plugin_from_id('web_services');
	if ($plugin) {
		$plugin->activate();
		$plugin->setPriority('first');
	}
}
