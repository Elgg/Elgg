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

		$CONFIG = _elgg_services()->config->getStorageObject();
		$local_path = Local::root()->getPath();

		// setup stuff available without any DB info
		$CONFIG->path = $local_path;
		$CONFIG->plugins_path = "{$local_path}mod/";
		$CONFIG->pluginspath = "{$local_path}mod/";
		$CONFIG->entity_types = ['group', 'object', 'site', 'user'];
		$CONFIG->language = 'en';

		// set cookie values for session and remember me
		_elgg_services()->config->getCookieConfig();

		// we need this stuff before cache
		$rows = $db->getData("
			SELECT *
			FROM {$db->getTablePrefix()}datalists
			WHERE `name` IN ('__site_secret__', 'default_site', 'dataroot')
		");
		$datalists = [];
		foreach ($rows as $row) {
			$datalists[$row->name] = $row->value;
		}

		// booting during installation
		if (empty($datalists['dataroot'])) {
			$datalists['dataroot'] = '';

			// don't use cache
			$CONFIG->boot_cache_ttl = 0;
		}

		if (!$GLOBALS['_ELGG']->dataroot_in_settings) {
			$CONFIG->dataroot = rtrim($datalists['dataroot'], '/\\') . DIRECTORY_SEPARATOR;
		}
		$CONFIG->site_guid = (int)$datalists['default_site'];
		$CONFIG->site_id = (int)$datalists['default_site'];
		if (!isset($CONFIG->boot_cache_ttl)) {
			$CONFIG->boot_cache_ttl = self::DEFAULT_BOOT_CACHE_TTL;
		}

		if ($this->timer) {
			$this->timer->begin([__CLASS__ . '::getBootData']);
		}

		// early config is done, now get the core boot data
		$data = $this->getBootData($CONFIG, $db);

		if ($this->timer) {
			$this->timer->begin([__CLASS__ . '::getBootData']);
		}

		// copy earlier fetches values into the datalist cache and inject into the service
		$datalist_cache = $data->getDatalistCache();
		foreach (['__site_secret__', 'default_site', 'dataroot'] as $key) {
			$datalist_cache->put($key, $datalists[$key]);
		}
		_elgg_services()->datalist->setCache($datalist_cache);

		$CONFIG->site = $data->getSite();
		$CONFIG->wwwroot = $CONFIG->site->url;
		$CONFIG->sitename = $CONFIG->site->name;
		$CONFIG->sitedescription = $CONFIG->site->description;
		$CONFIG->url = $CONFIG->wwwroot;

		_elgg_services()->subtypeTable->setCachedValues($data->getSubtypeData());

		foreach ($data->getConfigValues() as $key => $value) {
			$CONFIG->$key = $value;
		}

		_elgg_services()->plugins->setBootPlugins($data->getActivePlugins());

		_elgg_services()->pluginSettingsCache->setCachedValues($data->getPluginSettings());

		if (!$GLOBALS['_ELGG']->simplecache_enabled_in_settings) {
			$simplecache_enabled = $datalist_cache->get('simplecache_enabled');
			$CONFIG->simplecache_enabled = ($simplecache_enabled === false) ? 1 : $simplecache_enabled;
		}

		$system_cache_enabled = $datalist_cache->get('system_cache_enabled');
		$CONFIG->system_cache_enabled = ($system_cache_enabled === false) ? 1 : $system_cache_enabled;

		// needs to be set before [init, system] for links in html head
		$CONFIG->lastcache = (int)$datalist_cache->get("simplecache_lastupdate");

		$GLOBALS['_ELGG']->i18n_loaded_from_cache = false;

		if (!empty($CONFIG->debug)) {
			_elgg_services()->logger->setLevel($CONFIG->debug);
			_elgg_services()->logger->setDisplay(true);
		}

		$GLOBALS['_ELGG']->view_path = \Elgg\Application::elggDir()->getPath("/views/");

		// finish boot sequence
		_elgg_session_boot();
		_elgg_services()->systemCache->loadAll();
		_elgg_services()->translator->loadTranslations();

		// we always need site->email and user->icontime, so load them together
		$preload_md_guids = [$CONFIG->site_guid];
		$user_guid = _elgg_services()->session->getLoggedInUserGuid();
		if ($user_guid) {
			$preload_md_guids[] = $user_guid;
		}
		_elgg_services()->metadataCache->populateFromEntities($preload_md_guids);

		// TODO get rid of in 3.0, then we can drop the metadata preload for anon visitors
		$CONFIG->siteemail = $CONFIG->site->email;

		// gives hint to get() how to approach missing values
		$CONFIG->site_config_loaded = true;

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
	 * @param int $site_guid Site GUID or empty for current site
	 *
	 * @return void
	 */
	public function invalidateCache($site_guid = 0) {
		$CONFIG = _elgg_services()->config->getStorageObject();
		if (!$site_guid) {
			$site_guid = $CONFIG->site_guid;
		}

		// this gets called a lot on plugins page, avoid thrashing cache
		static $cleared = [];
		if (empty($cleared[$site_guid])) {
			$this->getStashItem($CONFIG, $site_guid)->clear();
			$cleared[$site_guid] = true;
		}
	}

	/**
	 * Get the boot data
	 *
	 * @param \stdClass $CONFIG Elgg config object
	 * @param Database  $db     Elgg database
	 *
	 * @return BootData
	 *
	 * @throws \InstallationException
	 */
	private function getBootData(\stdClass $CONFIG, Database $db) {
		$CONFIG->_boot_cache_hit = false;

		if (!$CONFIG->boot_cache_ttl) {
			$data = new BootData();
			$data->populate($CONFIG, $db, _elgg_services()->entityTable, _elgg_services()->plugins);
			return $data;
		}

		$item = $this->getStashItem($CONFIG, $CONFIG->site_guid);
		$item->setInvalidationMethod(Invalidation::NONE);
		$data = $item->get();
		if ($item->isMiss()) {
			$data = new BootData();
			$data->populate($CONFIG, $db, _elgg_services()->entityTable, _elgg_services()->plugins);
			$item->set($data);
			$item->expiresAfter($CONFIG->boot_cache_ttl);
			$item->save();
		} else {
			$CONFIG->_boot_cache_hit = true;
		}

		return $data;
	}

	/**
	 * Get a Stash cache item
	 *
	 * @param \stdClass $CONFIG    Elgg config object
	 * @param int       $site_guid The current site GUID
	 *
	 * @return \Stash\Interfaces\ItemInterface
	 */
	private function getStashItem(\stdClass $CONFIG, $site_guid) {
		if (!empty($CONFIG->memcache)) {
			$options = [];
			if (!empty($CONFIG->memcache_servers)) {
				$options['servers'] = $CONFIG->memcache_servers;
			}
			$driver = new Memcache($options);
		} else {
			if (!$CONFIG->dataroot) {
				// we're in the installer
				$driver = new BlackHole();
			} else {
				$driver = new FileSystem([
					'path' => $CONFIG->dataroot,
				]);
			}
		}
		return (new Pool($driver))->getItem("boot_data_{$site_guid}");
	}
}
