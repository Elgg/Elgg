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
	public function __invoke(): void {
		if ($this->app->getBootStatus('full_boot_completed')) {
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
	public function bootServices(): void {
		if ($this->app->getBootStatus('service_boot_completed')) {
			return;
		}

		// in case not loaded already
		$this->app->loadCore();

		if (!$this->app->internal_services->db) {
			// no database boot!
			elgg_views_boot();
			$this->app->internal_services->session->start();
			$this->app->internal_services->translator->bootTranslations();

			\Elgg\Application\SystemEventHandlers::init();

			$this->app->setBootStatus('full_boot_completed', true);

			return;
		}

		$this->setEntityClasses();
		
		// need to be registered as part of services, because partial boots do at least include the services
		// the system relies on the existence of some of the event
		$this->registerEvents();

		// Connect to database, load language files, load configuration, init session
		$this->app->internal_services->boot->boot($this->app->internal_services);
		
		// we don't store langs in boot data because it varies by user
		$this->app->internal_services->translator->bootTranslations();
		
		$this->app->setBootStatus('service_boot_completed', true);
	}

	/**
	 * Boot plugins
	 *
	 * @return void
	 */
	public function bootPlugins(): void {
		if ($this->app->getBootStatus('plugins_boot_completed') || !$this->app->internal_services->db) {
			return;
		}

		$events = $this->app->internal_services->events;

		$events->registerHandler('plugins_load:before', 'system', 'elgg_views_boot');
		$events->registerHandler('plugins_load:after', 'system', function() {
			$this->app->internal_services->session->boot();
		});

		$events->registerHandler('plugins_boot', 'system', function() {
			$this->registerRoutes();
		});
		$events->registerHandler('plugins_boot', 'system', function() {
			$this->registerActions();
		});

		// Setup all boot sequence handlers for active plugins
		$this->app->internal_services->plugins->build();

		// Register plugin classes, entities etc
		// Call PluginBootstrap::load()
		// After event completes, Elgg session is booted
		$events->triggerSequence('plugins_load', 'system');

		// Boot plugin, setup languages and views
		// Call PluginBootstrap::boot()
		$events->triggerSequence('plugins_boot', 'system');

		$this->app->setBootStatus('plugins_boot_completed', true);
	}

	/**
	 * Finish bootstrapping the application
	 *
	 * @return void
	 */
	public function bootApplication(): void {
		if ($this->app->getBootStatus('application_boot_completed') || !$this->app->internal_services->db) {
			return;
		}

		$events = $this->app->internal_services->events;

		$this->app->internal_services->views->clampViewtypeToPopulatedViews();
		$this->app->allowPathRewrite();

		// Complete the boot process for both engine and plugins
		$events->triggerSequence('init', 'system');

		$this->app->setBootStatus('full_boot_completed', true);

		// Tell the access functions the system has booted, plugins are loaded,
		// and the user is logged in so it can start caching
		$this->app->internal_services->accessCollections->markInitComplete();
		
		// System loaded and ready
		$events->triggerSequence('ready', 'system');

		$this->app->setBootStatus('application_boot_completed', true);
	}

	/**
	 * Set core entity classes
	 *
	 * @return void
	 */
	public function setEntityClasses(): void {
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
	
	/**
	 * Register core events
	 *
	 * @return void
	 */
	protected function registerEvents(): void {
		$conf = \Elgg\Project\Paths::elgg() . 'engine/events.php';
		$spec = \Elgg\Includer::includeFile($conf);
		
		$events = $this->app->internal_services->events;
		
		foreach ($spec as $name => $types) {
			foreach ($types as $type => $callbacks) {
				foreach ($callbacks as $callback => $event_spec) {
					if (!is_array($event_spec)) {
						continue;
					}
					
					$unregister = (bool) elgg_extract('unregister', $event_spec, false);
					
					if ($unregister) {
						$events->unregisterHandler($name, $type, $callback);
					} else {
						$priority = (int) elgg_extract('priority', $event_spec, 500);
						
						$events->registerHandler($name, $type, $callback, $priority);
					}
				}
			}
		}
	}
	
	/**
	 * Register core routes
	 *
	 * @return void
	 */
	protected function registerRoutes(): void {
		$conf = \Elgg\Project\Paths::elgg() . 'engine/routes.php';
		$routes = \Elgg\Includer::includeFile($conf);
	
		foreach ($routes as $name => $def) {
			$this->app->internal_services->routes->register($name, $def);
		}
	}
	
	/**
	 * Register core actions
	 *
	 * @return void
	 */
	protected function registerActions(): void {
		$conf = \Elgg\Project\Paths::elgg() . 'engine/actions.php';
		$actions = \Elgg\Includer::includeFile($conf);
		
		$root_path = \Elgg\Project\Paths::elgg();
	
		foreach ($actions as $action => $action_spec) {
			if (!is_array($action_spec)) {
				continue;
			}
			
			$access = elgg_extract('access', $action_spec, 'logged_in');
			$handler = elgg_extract('controller', $action_spec);
			if (!$handler) {
				$handler = elgg_extract('filename', $action_spec) ?: "{$root_path}/actions/{$action}.php";
			}
			
			$this->app->internal_services->actions->register($action, $handler, $access);
		}
	}
}
