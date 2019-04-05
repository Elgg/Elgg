<?php

namespace Elgg;

use Elgg\Database\SiteSecret;
use Elgg\Di\ServiceProvider;
use Elgg\Project\Paths;
use ElggCache;
use Stash\Invalidation;

/**
 * Boots Elgg and manages a cache of data needed during boot
 *
 * @access private
 * @since  2.1
 */
class BootService {

	use Profilable;
	use Cacheable;

	/**
	 * The default TTL if not set in settings.php
	 */
	const DEFAULT_BOOT_CACHE_TTL = 3600;

	/**
	 * The default limit for the number of plugin settings a plugin can have before it won't be loaded into bootdata
	 *
	 * Can be set in settings.php
	 */
	const DEFAULT_BOOTDATA_PLUGIN_SETTINGS_LIMIT = 40;

	/**
	 * Cache
	 *
	 * @param ElggCache $cache Cache
	 */
	public function __construct(ElggCache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Boots the engine
	 *
	 * @param ServiceProvider $services Services
	 *
	 * @return void
	 * @throws \ClassException
	 * @throws \DatabaseException
	 * @throws \InstallationException
	 * @throws \InvalidParameterException
	 * @throws \SecurityException
	 */
	public function boot(ServiceProvider $services) {
		$db = $services->db;
		$config = $services->config;

		// set cookie values for session and remember me
		$config->getCookieConfig();

		// defaults in case these aren't in config table
		if ($config->boot_cache_ttl === null) {
			$config->boot_cache_ttl = self::DEFAULT_BOOT_CACHE_TTL;
		}
		if ($config->bootdata_plugin_settings_limit === null) {
			$config->bootdata_plugin_settings_limit = self::DEFAULT_BOOTDATA_PLUGIN_SETTINGS_LIMIT;
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
		if ($config->min_password_length === null) {
			$config->min_password_length = 6;
		}
		if ($config->minusername === null) {
			$config->minusername = 4;
		}
		if ($config->batch_run_time_in_secs === null) {
			$config->batch_run_time_in_secs = 4;
		}

		// we were using NOTICE temporarily so we can't just check for null
		if (!$config->hasInitialValue('debug') && !$config->debug) {
			$config->debug = '';
		}

		// copy all table values into config
		$config->mergeValues($services->configTable->getAll());

		// needs to be set before [init, system] for links in html head
		$config->lastcache = (int) $config->simplecache_lastupdate;

		if (!$config->elgg_config_set_secret) {
			$site_secret = SiteSecret::fromConfig($config);
			if ($site_secret) {
				$services->setValue('siteSecret', $site_secret);
			} else {
				throw new \RuntimeException('The site secret is not set.');
			}
		}

		$installed = isset($config->installed);

		if ($this->timer) {
			$this->timer->begin([__CLASS__ . '::getBootData']);
		}

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

		$settings = $data->getPluginSettings();
		foreach ($settings as $guid => $entity_settings) {
			$services->privateSettingsCache->save($guid, $entity_settings);
		}

		foreach ($data->getPluginMetadata() as $guid => $metadata) {
			$services->dataCache->metadata->save($guid, $metadata);
		}

		$services->plugins->setBootPlugins($data->getActivePlugins(), false);

		// use value in settings.php if available
		$debug = $config->hasInitialValue('debug') ? $config->getInitialValue('debug') : $config->debug;
		$services->logger->setLevel($debug);

		if ($config->system_cache_enabled) {
			$config->system_cache_loaded = false;

			if ($services->views->configureFromCache($services->systemCache)) {
				$config->system_cache_loaded = true;
			}
		}

		// we don't store langs in boot data because it varies by user
		$services->translator->bootTranslations();

		// invalidate on some actions just in case other invalidation triggers miss something
		$services->hooks->registerHandler('action', 'all', function ($action) {
			if (0 === strpos($action, 'admin/' || $action === 'plugins/settings/save')) {
				$this->invalidateCache();
			}
		}, 1);
	}

	/**
	 * Invalidate the cache item
	 *
	 * @return void
	 */
	public function invalidateCache() {
		$this->cache->clear();
		_elgg_services()->plugins->setBootPlugins(null);
		_elgg_config()->system_cache_loaded = false;
		_elgg_config()->_boot_cache_hit = false;
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

		$data = null;
		if ($config->boot_cache_ttl > 0) {
			$data = $this->cache->load('boot_data');
		}

		if (!isset($data)) {
			$data = new BootData();
			$data->populate($config, $db, _elgg_services()->entityTable, _elgg_services()->plugins, $installed);
			if ($config->boot_cache_ttl && $installed) {
				$this->cache->save('boot_data', $data, $config->boot_cache_ttl);
			}
		} else {
			$config->_boot_cache_hit = true;
		}

		return $data;
	}

}
