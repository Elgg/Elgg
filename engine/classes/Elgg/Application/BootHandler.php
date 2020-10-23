<?php

namespace Elgg\Application;

use Elgg\Application;

/**
 * Handles application boot sequence
 *
 * @internal
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
	 */
	public function __invoke() {
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
	 *
	 * @return void
	 */
	public function bootServices() {
		$config = $this->app->_services->config;

		if ($config->_service_boot_complete) {
			return;
		}

		// in case not loaded already
		$this->app->loadCore();

		if (!$this->app->_services->db) {
			// no database boot!
			elgg_views_boot();
			$this->app->_services->session->start();
			$this->app->_services->translator->bootTranslations();

			\Elgg\Application\SystemEventHandlers::init();

			$config->boot_complete = true;
			$config->lock('boot_complete');

			return;
		}

		$this->setEntityClasses();
		
		// need to be registered as part of services, because partial boots do at least include the services
		// the system relies on the existence of some of the event/hooks
		_elgg_register_hooks();
		_elgg_register_events();

		// Connect to database, load language files, load configuration, init session
		$this->app->_services->boot->boot($this->app->_services);

		$config->_service_boot_complete = true;
		$config->lock('_service_boot_complete');
	}

	/**
	 * Boot plugins
	 *
	 * @return void
	 */
	public function bootPlugins() {
		$config = $this->app->_services->config;

		if ($config->_plugins_boot_complete || !$this->app->_services->db) {
			return;
		}

		$events = $this->app->_services->events;

		$events->registerHandler('plugins_load:before', 'system', 'elgg_views_boot');
		$events->registerHandler('plugins_load:after', 'system', function() {
			_elgg_session_boot($this->app->_services);
		});

		$events->registerHandler('plugins_boot', 'system', '_elgg_register_routes');
		$events->registerHandler('plugins_boot', 'system', '_elgg_register_actions');

		// Setup all boot sequence handlers for active plugins
		$this->app->_services->plugins->build();

		// Register plugin classes, entities etc
		// Call PluginBootstrap::load()
		// After event completes, Elgg session is booted
		$events->triggerSequence('plugins_load', 'system');

		// Boot plugin, setup languages and views
		// Call PluginBootstrap::boot()
		$events->triggerSequence('plugins_boot', 'system');

		$config->_plugins_boot_complete = true;
		$config->lock('_plugins_boot_complete');
	}

	/**
	 * Finish bootstrapping the application
	 *
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

		// Tell the access functions the system has booted, plugins are loaded,
		// and the user is logged in so it can start caching
		$this->app->_services->accessCollections->markInitComplete();
		
		// System loaded and ready
		$events->triggerSequence('ready', 'system');

		$config->_application_boot_complete = true;
		$config->lock('_application_boot_complete');
	}

	/**
	 * Set core entity classes
	 *
	 * @return void
	 */
	public function setEntityClasses() {
		elgg_set_entity_class('user', 'user', \ElggUser::class);
		elgg_set_entity_class('group', 'group', \ElggGroup::class);
		elgg_set_entity_class('site', 'site', \ElggSite::class);
		elgg_set_entity_class('object', 'plugin', \ElggPlugin::class);
		elgg_set_entity_class('object', 'file', \ElggFile::class);
		elgg_set_entity_class('object', 'widget', \ElggWidget::class);
		elgg_set_entity_class('object', 'comment', \ElggComment::class);
		elgg_set_entity_class('object', 'elgg_upgrade', \ElggUpgrade::class);
		elgg_set_entity_class('object', 'admin_notice', \ElggAdminNotice::class);
	}
}
