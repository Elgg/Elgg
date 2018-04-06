<?php

class BootstrapPluginTestBootstrap extends \Elgg\PluginBootstrap {

	/**
	 * Executed during 'plugins_boot:before', 'system' event
	 *
	 * Allows the plugin to require additional files, as well as configure services prior to booting the plugin
	 *
	 * @return void
	 */
	public function load() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Executed during 'plugins_boot:before', 'system' event
	 *
	 * Allows the plugin to register handlers for 'plugins_boot', 'system' and 'init', 'system' events,
	 * as well as implement boot time logic
	 *
	 * @return void
	 */
	public function boot() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Executed during 'init', 'system' event
	 *
	 * Allows the plugin to implement business logic and register all other handlers
	 *
	 * @return void
	 */
	public function init() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Executed during 'ready', 'system' event
	 *
	 * Allows the plugin to implement logic after all plugins are initialized
	 *
	 * @return void
	 */
	public function ready() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Executed during 'shutdown', 'system' event
	 *
	 * Allows the plugin to implement logic during shutdown
	 *
	 * @return void
	 */
	public function shutdown() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Executed when plugin is activated, after 'activate', 'plugin' event and before activate.php is included
	 *
	 * @return void
	 */
	public function activate() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Executed when plugin is deactivated, after 'deactivate', 'plugin' event and before deactivate.php is included
	 *
	 * @return void
	 */
	public function deactivate() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

	/**
	 * Registered as handler for 'upgrade', 'system' event
	 *
	 * Allows the plugin to implement logic during system upgrade
	 *
	 * @return void
	 */
	public function upgrade() {
		$prop = __METHOD__ . '_calls';
		$this->plugin->$prop++;
	}

}