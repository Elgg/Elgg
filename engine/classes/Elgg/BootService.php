<?php

namespace Elgg;

use Stash\Driver\BlackHole;
use Stash\Driver\FileSystem;
use Stash\Driver\Memcache;
use Stash\Invalidation;
use Stash\Pool;
use Elgg\Di\ServiceProvider;

/**
 * Boots Elgg and manages a cache of data needed during boot
 *
 * @access private
 * @since 2.1
 */
class BootService {
	use Profilable;

	/**
	 * Under load, a short TTL gives nearly all of the benefits of a longer TTL, but it also ensures
	 * that, should cache invalidation fail for some reason, it'll be rebuilt quickly anyway.
	 *
	 * In 2.x we do not cache by default. This will likely change to 10 in 3.0.
	 */
	const DEFAULT_BOOT_CACHE_TTL = 0;

	/**
	 * Boots the engine
	 *
	 * @param ServiceProvider $services Services
	 * @return void
	 */
	public function boot(ServiceProvider $services) {
		$db = $services->db;
		$config = $services->config;

		// we inject the logger here to allow use of DB without loading core
		$db->setLogger($services->logger);
		$config->setLogger($services->logger);

		// set cookie values for session and remember me
		$config->getCookieConfig();

		if ($config->boot_cache_ttl === null) {
			$config->boot_cache_ttl = self::DEFAULT_BOOT_CACHE_TTL;
		}

		if ($this->timer) {
			$this->timer->begin([__CLASS__ . '::getBootData']);
		}

		// early config is done, now get the core boot data
		$data = $this->getBootData($config, $db);

		if ($this->timer) {
			$this->timer->begin([__CLASS__ . '::getBootData']);
		}

		$configs_cache = $data->getConfigValues();

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

		$services->subtypeTable->setCachedValues($data->getSubtypeData());

		$config->setValues($data->getConfigValues());

		// set this up right away so we get it removed from config and know if it's going to fail
		$services->siteSecret->get();

		$services->plugins->setBootPlugins($data->getActivePlugins());

		$services->pluginSettingsCache->setCachedValues($data->getPluginSettings());

		if (!$config->_simplecache_enabled_in_settings) {
			$simplecache_enabled = $configs_cache['simplecache_enabled'];
			$config->simplecache_enabled = ($simplecache_enabled === false) ? 1 : $simplecache_enabled;
		}

		$system_cache_enabled = $configs_cache['system_cache_enabled'];
		$config->system_cache_enabled = ($system_cache_enabled === false) ? 1 : $system_cache_enabled;

		// needs to be set before [init, system] for links in html head
		$config->lastcache = (int) $configs_cache['simplecache_lastupdate'];

		if ($config->debug) {
			$services->logger->setLevel($config->debug);
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

		// we always need site->email and user->icontime, so load them together
		$user_guid = $services->session->getLoggedInUserGuid();
		if ($user_guid) {
			$services->metadataCache->populateFromEntities([$user_guid]);
		}

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
		// this gets called a lot on plugins page, avoid thrashing cache
		static $cleared = false;
		if (!$cleared) {
			$this->getStashItem(_elgg_config())->clear();
			$cleared = true;
		}
	}

	/**
	 * Get the boot data
	 *
	 * @param Config   $config Elgg config object
	 * @param Database $db     Elgg database
	 *
	 * @return BootData
	 *
	 * @throws \InstallationException
	 */
	private function getBootData(Config $config, Database $db) {
		$config->_boot_cache_hit = false;

		if (!$config->boot_cache_ttl) {
			$data = new BootData();
			$data->populate($db, _elgg_services()->entityTable, _elgg_services()->plugins);
			return $data;
		}

		$item = $this->getStashItem($config);
		$item->setInvalidationMethod(Invalidation::NONE);
		$data = $item->get();
		if ($item->isMiss()) {
			$data = new BootData();
			$data->populate($db, _elgg_services()->entityTable, _elgg_services()->plugins);
			$item->set($data);
			$item->expiresAfter($config->boot_cache_ttl);
			$item->save();
		} else {
			$config->_boot_cache_hit = true;
		}

		return $data;
	}

	/**
	 * Get a Stash cache item
	 *
	 * @param Config $config Elgg config
	 *
	 * @return \Stash\Interfaces\ItemInterface
	 */
	private function getStashItem(Config $config) {
		if ($config->memcache && class_exists('Memcache')) {
			$options = [];
			if ($config->memcache_servers) {
				$options['servers'] = $config->memcache_servers;
			}
			$driver = new Memcache($options);
		} else {
			if (!$config->dataroot) {
				// we're in the installer
				$driver = new BlackHole();
			} else {
				$driver = new FileSystem([
					'path' => $config->dataroot,
				]);
			}
		}
		return (new Pool($driver))->getItem("boot_data");
	}
}
