<?php

namespace Elgg;

use Elgg\Database\SiteSecret;
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
	 * Has the cache already been invalidated this request? Avoids thrashing
	 *
	 * @var bool
	 */
	private $was_cleared = false;

	/**
	 * Boots the engine
	 *
	 * @param ServiceProvider $services Services
	 * @return void
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
		$config->mergeValues($services->configTable->getAll());

		// needs to be set before [init, system] for links in html head
		$config->lastcache = (int) $config->simplecache_lastupdate;

		if (!$config->elgg_config_set_secret) {
			$site_secret = SiteSecret::fromConfig($config);
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

		$services->plugins->setBootPlugins($data->getActivePlugins());

		$services->pluginSettingsCache->setCachedValues($data->getPluginSettings());

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
		if (!$this->was_cleared) {
			$this->getStashItem(_elgg_config())->clear();
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
	 * @throws \InstallationException
	 */
	private function getBootData(Config $config, Database $db, $installed) {
		$config->_boot_cache_hit = false;

		if (!$config->boot_cache_ttl) {
			$data = new BootData();
			$data->populate($db, _elgg_services()->entityTable, _elgg_services()->plugins, $installed);
			return $data;
		}

		$item = $this->getStashItem($config);
		$item->setInvalidationMethod(Invalidation::NONE);
		$data = $item->get();
		if ($item->isMiss()) {
			$data = new BootData();
			$data->populate($db, _elgg_services()->entityTable, _elgg_services()->plugins, $installed);
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
		if ($config->memcache && class_exists('Memcached')) {
			$options = [];
			if ($config->memcache_servers) {
				$options['servers'] = $config->memcache_servers;
			}
			$driver = new \Memcached($options);
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
