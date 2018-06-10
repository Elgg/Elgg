<?php

namespace Elgg\Application;

use Elgg\Application;

/**
 * Handles application boot sequence
 */
class BootHandler {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * Constructor
	 *
	 * @param Application $app Unbooted application
	 */
	public function __construct(Application $app) {
		$this->app = $app;
	}

	/**
	 * Full application boot
	 * Boots services, plugins and trigger init/ready events
	 *
	 * @return void
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 * @throws \SecurityException
	 */
	public function init() {
		$config = $this->app->_services->config;

		if ($config->boot_complete) {
			return;
		}

		$this->bootServices();
		$this->bootPlugins();
		$this->bootApplication();
	}

	/**
	 * Boot core services
	 * @return void
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 * @throws \SecurityException
	 */
	public function bootServices() {
		$config = $this->app->_services->config;

		if ($config->_service_boot_complete) {
			return;
		}

		// in case not loaded already
		$setups = $this->app->loadCore();

		$hooks = $this->app->_services->hooks;
		$events = $this->app->_services->events;

		foreach ($setups as $setup) {
			if ($setup instanceof \Closure) {
				$setup($events, $hooks);
			}
		}

		if (!$this->app->_services->db) {
			// no database boot!
			elgg_views_boot();
			$this->app->_services->session->start();
			$this->app->_services->translator->loadTranslations();

			_elgg_init();
			_elgg_input_init();
			_elgg_nav_init();

			$config->boot_complete = true;
			$config->lock('boot_complete');

			return;
		}

		// Connect to database, load language files, load configuration, init session
		$this->app->_services->boot->boot($this->app->_services);

		$config->_service_boot_complete = true;
		$config->lock('_service_boot_complete');
	}

	/**
	 * Boot plugins
	 * @return void
	 */
	public function bootPlugins() {
		$config = $this->app->_services->config;

		if ($config->_plugins_boot_complete || !$this->app->_services->db) {
			return;
		}

		$events = $this->app->_services->events;

		$events->registerHandler('plugins_boot:before', 'system', 'elgg_views_boot');
		$events->registerHandler('plugins_boot', 'system', '_elgg_register_routes');
		$events->registerHandler('plugins_boot', 'system', '_elgg_register_actions');

		// Load the plugins that are active
		$this->app->_services->plugins->load();

		// Allows registering handlers strictly before all init, system handlers
		$events->triggerSequence('plugins_boot', 'system');

		$this->plugins_boot_complete = true;

		$config->_plugins_boot_complete = true;
		$config->lock('_plugins_boot_complete');
	}

	/**
	 * Finish bootstrapping the application
	 * @return void
	 */
	public function bootApplication() {
		$config = $this->app->_services->config;

		if ($config->_application_boot_complete || !$this->app->_services->db) {
			return;
		}

		$events = $this->app->_services->events;

		$this->app->_services->views->clampViewtypeToPopulatedViews();
		$this->app->allowPathRewrite();

		// Complete the boot process for both engine and plugins
		$events->triggerSequence('init', 'system');

		$config->boot_complete = true;
		$config->lock('boot_complete');

		// System loaded and ready
		$events->triggerSequence('ready', 'system');

		$config->_application_boot_complete = true;
		$config->lock('_application_boot_complete');
	}
}