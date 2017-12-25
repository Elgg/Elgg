<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\BootData;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\SiteSecret;
use Elgg\Di\ServiceProvider;
use Elgg\Includer;
use Elgg\Profilable;
use Elgg\Project\Paths;
use ElggCache;
use Stash\Invalidation;

/**
 * Boots Elgg and manages a cache of data needed during boot
 *
 * @access private
 * @since  2.1
 */
class BootHandler {

	use Profilable;

	/**
	 * Under load, a short TTL gives nearly all of the benefits of a longer TTL, but it also ensures
	 * that, should cache invalidation fail for some reason, it'll be rebuilt quickly anyway.
	 *
	 * In 2.x we do not cache by default. This will likely change to 10 in 3.0.
	 */
	const DEFAULT_BOOT_CACHE_TTL = 0;

	/**
	 * Has the cache already been invalidated this request? Avoids thrashing
	 *
	 * @var bool
	 */
	private $was_cleared = false;

	/**
	 * @var ElggCache
	 */
	protected $cache;

	/**
	 * @var Application
	 */
	protected $application;

	/**
	 * Cache
	 *
	 * @param Application $application Application
	 * @param ElggCache   $cache       Cache
	 */
	public function __construct(Application $application, ElggCache $cache) {
		$this->application = $application;
		$this->cache = $cache;
	}

	/**
	 * Bootstrap the Elgg engine, loads plugins, and calls initial system events
	 *
	 * This method loads the full Elgg engine, checks the installation
	 * state, and triggers a series of events to finish booting Elgg:
	 *    - {@elgg_event boot system}
	 *    - {@elgg_event init system}
	 *    - {@elgg_event ready system}
	 *
	 * If Elgg is not fully installed, the browser will be redirected to an installation page.
	 *
	 * @return void
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 * @throws \PluginException
	 * @throws \SecurityException
	 */
	public function boot() {
		$config = $this->application->_services->config;

		if ($config->boot_complete) {
			return;
		}

		Bootstrap::loadCore();

		$hooks = $this->application->_services->hooks;
		$events = $hooks->getEvents();

		foreach (Bootstrap::getSetups() as $setup) {
			$setup($events, $hooks);
		}

		if (!$this->application->_services->db) {
			// no database boot!
			elgg_views_boot();
			$this->application->_services->session->start();
			$this->application->_services->translator->loadTranslations();

			actions_init();
			_elgg_init();
			_elgg_input_init();
			_elgg_nav_init();

			$config->boot_complete = true;
			$config->lock('boot_complete');

			return;
		}

		// Connect to database, load language files, load configuration, init session
		$this->bootstrap();

		elgg_views_boot();

		// Load the plugins that are active
		$this->application->_services->plugins->load();

		$this->initRootPlugin($config);

		// after plugins are started we know which viewtypes are populated
		$this->application->_services->views->clampViewtypeToPopulatedViews();

		$this->allowPathRewrite();

		// Allows registering handlers strictly before all init, system handlers
		$events->trigger('plugins_boot', 'system');

		// Complete the boot process for both engine and plugins
		$events->trigger('init', 'system');

		$config->boot_complete = true;
		$config->lock('boot_complete');

		// System loaded and ready
		$events->trigger('ready', 'system');
	}


	/**
	 * Allow plugins to rewrite the path.
	 *
	 * @return void
	 */
	private function allowPathRewrite() {
		$request = $this->application->_services->request;
		$new = $this->application->_services->router->allowRewrite($request);
		if ($new === $request) {
			return;
		}

		$this->application->_services->setValue('request', $new);
		$this->application->_services->context->initialize($new);
	}

	/**
	 * Bootstraps the application without triggering the boot sequence
	 *
	 * @return void
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 * @throws \SecurityException
	 */
	public function bootstrap() {

		Bootstrap::loadCore();

		$services = $this->application->_services;

		$config = $services->config;
		$db = $services->db;

		$this->configure($config);

		$this->initSiteSecret($services);

		if ($this->timer) {
			$this->timer->begin([__CLASS__ . '::getBootData']);
		}

		$installed = isset($config->installed);

		// early config is done, now get the core boot data
		$data = $this->getBootData($config, $db, $installed);

		$site = $data->getSite();
		if (!$site) {
			// must be set in config
			$site = $config->site;
			if (!$site instanceof \ElggSite) {
				throw new \RuntimeException('Before installation, config->site must have an unsaved ElggSite.');
			}
		}
		$config->site = $site;
		$config->sitename = $site->name;
		$config->sitedescription = $site->description;

		$services->plugins->setBootPlugins($data->getActivePlugins());

		$settings = $data->getPluginSettings();
		foreach ($settings as $guid => $entity_settings) {
			$services->privateSettingsCache->save($guid, $entity_settings);
		}

		foreach ($data->getPluginMetadata() as $guid => $metadata) {
			$services->dataCache->metadata->save($guid, $metadata);
		}

		$services->logger->setLevel($config->debug);
		if ($config->debug) {
			$services->logger->setDisplay(true);
		}

		// finish boot sequence
		_elgg_session_boot($services);

		if ($config->system_cache_enabled) {
			$config->system_cache_loaded = false;

			if ($services->views->configureFromCache($services->systemCache)) {
				$config->system_cache_loaded = true;
			}
		}

		// we don't store langs in boot data because it varies by user
		$services->translator->loadTranslations();

		// invalidate on some actions just in case other invalidation triggers miss something
		$services->hooks->registerHandler('action', 'all', function ($action) {
			if (0 === strpos($action, 'admin/' || $action === 'plugins/settings/save')) {
				$this->invalidateCache();
			}
		}, 1);
	}

	/**
	 * Popuplate boot config values
	 *
	 * @param Config $config Config
	 *
	 * @return void
	 */
	protected function configure(Config $config) {
		// set cookie values for session and remember me
		$config->getCookieConfig();

		// defaults in case these aren't in config table
		if ($config->boot_cache_ttl === null) {
			$config->boot_cache_ttl = BootHandler::DEFAULT_BOOT_CACHE_TTL;
		}
		if ($config->simplecache_enabled === null) {
			$config->simplecache_enabled = 0;
		}
		if ($config->system_cache_enabled === null) {
			$config->system_cache_enabled = false;
		}
		if ($config->simplecache_lastupdate === null) {
			$config->simplecache_lastupdate = 0;
		}

		// we were using NOTICE temporarily so we can't just check for null
		if (!$config->hasInitialValue('debug') && !$config->debug) {
			$config->debug = '';
		}

		// copy all table values into config
		$config->mergeValues($this->application->_services->configTable->getAll());

		// needs to be set before [init, system] for links in html head
		$config->lastcache = (int) $config->simplecache_lastupdate;
	}

	/**
	 * Initialize site secret
	 *
	 * @param ServiceProvider $services Service provider
	 * @return void
	 */
	public function initSiteSecret(ServiceProvider $services) {
		if ($services->config->elgg_config_set_secret) {
			return;
		}
		$site_secret = SiteSecret::fromConfig($services->config);
		if (!$site_secret) {
			// The 2.3 installer doesn't create a site key (it's created on-demand on the first request)
			// so for our Travis upgrade testing we need to check for this and create one on the spot.
			if (defined('UPGRADING')) {
				$site_secret = SiteSecret::regenerate($services->crypto, $services->configTable);
			}
		}
		if ($site_secret) {
			$services->setValue('siteSecret', $site_secret);
		} else {
			throw new \RuntimeException('The site secret is not set.');
		}
	}

	/**
	 * Initialize root level plugin
	 *
	 * @param Config $config Config
	 *
	 * @return void
	 */
	protected function initRootPlugin(Config $config) {
		if (Paths::project() === Paths::elgg()) {
			return;
		}
		// Elgg is installed as a composer dep, so try to treat the root directory
		// as a custom plugin that is always loaded last and can't be disabled...
		if (!$config->system_cache_loaded) {
			// configure view locations for the custom plugin (not Elgg core)
			$viewsFile = Paths::project() . 'views.php';
			if (is_file($viewsFile)) {
				$viewsSpec = Includer::includeFile($viewsFile);
				if (is_array($viewsSpec)) {
					$this->application->_services->views->mergeViewsSpec($viewsSpec);
				}
			}

			// find views for the custom plugin (not Elgg core)
			$this->application->_services->views->registerPluginViews(Paths::project());
		}

		if (!$config->i18n_loaded_from_cache) {
			$this->application->_services->translator->registerPluginTranslations(Paths::project());
		}

		// This is root directory start.php
		$root_start = Paths::project() . "start.php";
		if (is_file($root_start)) {
			Includer::requireFile($root_start);
		}
	}

	/**
	 * Invalidate the cache item
	 *
	 * @return void
	 */
	public function invalidateCache() {
		if (!$this->was_cleared) {
			$this->cache->clear();
			$this->was_cleared = true;
		}
	}

	/**
	 * Get the boot data
	 *
	 * @param Config   $config    Elgg config object
	 * @param Database $db        Elgg database
	 * @param bool     $installed Is the site installed?
	 *
	 * @return BootData
	 *
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 */
	private function getBootData(Config $config, Database $db, $installed) {
		$config->_boot_cache_hit = false;

		$data = $this->cache->load('boot_data');
		if (!isset($data)) {
			$data = new BootData();
			$data->populate($db, _elgg_services()->entityTable, _elgg_services()->plugins, $installed);
			if ($config->boot_cache_ttl) {
				$this->cache->save('boot_data', $data, $config->boot_cache_ttl, [Invalidation::NONE]);
			}
		} else {
			$config->_boot_cache_hit = true;
		}

		return $data;
	}

}
