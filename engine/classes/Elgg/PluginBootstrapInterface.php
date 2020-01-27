<?php

namespace Elgg;

use Elgg\Di\PublicContainer;
use ElggPlugin;

/**
 * Plugin bootstrap interface
 */
interface PluginBootstrapInterface {

	/**
	 * Executed during 'plugins_load', 'system' event
	 *
	 * Allows the plugin to require additional files, as well as configure services prior to booting the plugin
	 *
	 * @return void
	 */
	public function load();

	/**
	 * Executed during 'plugins_boot:before', 'system' event
	 *
	 * Allows the plugin to register handlers for 'plugins_boot', 'system' and 'init', 'system' events,
	 * as well as implement boot time logic
	 *
	 * @return void
	 */
	public function boot();

	/**
	 * Executed during 'init', 'system' event
	 *
	 * Allows the plugin to implement business logic and register all other handlers
	 *
	 * @return void
	 */
	public function init();

	/**
	 * Executed during 'ready', 'system' event
	 *
	 * Allows the plugin to implement logic after all plugins are initialized
	 *
	 * @return void
	 */
	public function ready();

	/**
	 * Executed during 'shutdown', 'system' event
	 *
	 * Allows the plugin to implement logic during shutdown
	 *
	 * @return void
	 */
	public function shutdown();

	/**
	 * Executed when plugin is activated, after 'activate', 'plugin' event
	 *
	 * @return void
	 */
	public function activate();

	/**
	 * Executed when plugin is deactivated, after 'deactivate', 'plugin' event
	 *
	 * @return void
	 */
	public function deactivate();

	/**
	 * Registered as handler for 'upgrade', 'system' event
	 *
	 * Allows the plugin to implement logic during system upgrade
	 *
	 * @return void
	 */
	public function upgrade();

	/**
	 * Returns Elgg's public DI container
	 * @return PublicContainer
	 */
	public function elgg();

	/**
	 * Returns plugin entity this bootstrap is related to
	 * @return ElggPlugin
	 */
	public function plugin();
}
