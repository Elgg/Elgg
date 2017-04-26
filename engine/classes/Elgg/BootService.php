<?php

namespace Elgg;

use Elgg\Filesystem\Directory\Local;
use Stash\Driver\BlackHole;
use Stash\Driver\FileSystem;
use Stash\Driver\Memcache;
use Stash\Invalidation;
use Stash\Pool;

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
	 * @return void
	 */
	public function boot() {
		// Register the error handlers
		set_error_handler('_elgg_php_error_handler');
		set_exception_handler('_elgg_php_exception_handler');

		$db = _elgg_services()->db;

		// we inject the logger here to allow use of DB without loading core
		$db->setLogger(_elgg_services()->logger);

		$db->setupConnections();
		$db->assertInstalled();

		$config = _elgg_services()->config;
		$local_path = Local::root()->getPath();

		// setup stuff available without any DB info
		$config->set('path', $local_path);
		$config->set('plugins_path', "{$local_path}mod/");
		$config->set('pluginspath', "{$local_path}mod/");
		$config->set('entity_types', ['group', 'object', 'site', 'user']);
		$config->set('language', 'en');

		// set cookie values for session and remember me
		_elgg_services()->config->getCookieConfig();

		$config->set('site_guid', 1);
		if ($config->get('boot_cache_ttl') === null) {
			$config->set('boot_cache_ttl', self::DEFAULT_BOOT_CACHE_TTL);
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
		$config->set('site', $site);
		$config->set('sitename', $site->name);
		$config->set('sitedescription', $site->description);
		$config->set('url', $config->get('wwwroot'));

		_elgg_services()->subtypeTable->setCachedValues($data->getSubtypeData());

		foreach ($data->getConfigValues() as $key => $value) {
			$config->set($key, $value);
		}

		_elgg_services()->plugins->setBootPlugins($data->getActivePlugins());

		_elgg_services()->pluginSettingsCache->setCachedValues($data->getPluginSettings());

		if (!$config->get('_simplecache_enabled_in_settings')) {
			$simplecache_enabled = $configs_cache['simplecache_enabled'];
			$config->set('simplecache_enabled', ($simplecache_enabled === false) ? 1 : $simplecache_enabled);
		}

		$system_cache_enabled = $configs_cache['system_cache_enabled'];
		$config->set('system_cache_enabled', ($system_cache_enabled === false) ? 1 : $system_cache_enabled);

		// needs to be set before [init, system] for links in html head
		$config->set('lastcache', (int) $configs_cache['simplecache_lastupdate']);

		if ($config->get('debug')) {
			_elgg_services()->logger->setLevel($config->get('debug'));
			_elgg_services()->logger->setDisplay(true);
		}

		// finish boot sequence
		_elgg_session_boot();
		if ($config->get('system_cache_enabled')) {
			_elgg_services()->systemCache->loadAll();
		}

		// we don't store langs in boot data because it varies by user
		_elgg_services()->translator->loadTranslations();

		// we always need site->email and user->icontime, so load them together
		$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		if ($user_guid) {
			_elgg_services()->metadataCache->populateFromEntities([$user_guid]);
		}

		// invalidate on some actions just in case other invalidation triggers miss something
		_elgg_services()->hooks->registerHandler('action', 'all', function ($action) {
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
			$this->getStashItem(_elgg_services()->config)->clear();
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
		$config->set('_boot_cache_hit', false);

		if (!$config->get('boot_cache_ttl')) {
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
			$item->expiresAfter($config->get('boot_cache_ttl'));
			$item->save();
		} else {
			$config->set('_boot_cache_hit', true);
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
		if ($config->get('memcache') && class_exists('Memcache')) {
			$options = [];
			if ($config->get('memcache_servers')) {
				$options['servers'] = $config->get('memcache_servers');
			}
			$driver = new Memcache($options);
		} else {
			if (!$config->get('dataroot')) {
				// we're in the installer
				$driver = new BlackHole();
			} else {
				$driver = new FileSystem([
					'path' => $config->getDataPath(),
				]);
			}
		}
		return (new Pool($driver))->getItem("boot_data");
	}
}
