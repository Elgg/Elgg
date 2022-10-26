<?php

namespace Elgg;

use Elgg\Cache\BaseCache;
use Elgg\Di\InternalContainer;
use Elgg\Exceptions\RuntimeException;
use Elgg\Traits\Cacheable;
use Elgg\Traits\Debug\Profilable;
use Psr\Log\LogLevel;

/**
 * Boots Elgg and manages a cache of data needed during boot
 *
 * @internal
 * @since  2.1
 */
class BootService {

	use Profilable;
	use Cacheable;

	/**
	 * Constructs the bootservice
	 *
	 * @param BaseCache $cache Cache
	 */
	public function __construct(BaseCache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Boots the engine
	 *
	 * @param InternalContainer $services Internal services
	 *
	 * @return void
	 * @throws RuntimeException
	 */
	public function boot(InternalContainer $services) {
		$config = $services->config;

		// we were using NOTICE temporarily so we can't just check for null
		if (!$config->hasInitialValue('debug') && !$config->debug) {
			$config->debug = '';
		}

		// copy all table values into config
		foreach ($services->configTable->getAll() as $name => $value) {
			$config->$name = $value;
		}
		
		// prevent some data showing up in $config
		foreach ($config::SENSITIVE_PROPERTIES as $name) {
			unset($config->{$name});
		}

		// early config is done, now get the core boot data
		$data = $this->getBootData($config, $config->hasValue('installed'));

		$site = $data->getSite();
		if ($site) {
			$config->site = $site;
		} else {
			// must be set in config
			$site = $config->site;
			if (!$site instanceof \ElggSite) {
				throw new RuntimeException('Before installation, config->site must have an unsaved ElggSite.');
			}
		}

		foreach ($data->getPluginMetadata() as $guid => $metadata) {
			$services->dataCache->metadata->save($guid, $metadata);
		}

		$services->plugins->setBootPlugins($data->getActivePlugins(), false);

		// use value in settings.php if available
		$debug = $config->getInitialValue('debug') ?? ($config->debug ?: LogLevel::CRITICAL);
		$services->logger->setLevel($debug);

		if ($config->system_cache_enabled) {
			$config->system_cache_loaded = $services->views->configureFromCache($services->serverCache);
		}
	}

	/**
	 * Clear the cache item
	 *
	 * @return void
	 */
	public function clearCache() {
		$this->cache->clear();
		_elgg_services()->plugins->setBootPlugins(null);
		_elgg_services()->config->system_cache_loaded = false;
		_elgg_services()->config->_boot_cache_hit = false;
	}

	/**
	 * Get the boot data
	 *
	 * @param Config $config    Elgg config object
	 * @param bool   $installed Is the site installed?
	 *
	 * @return BootData
	 */
	private function getBootData(Config $config, bool $installed) {
		$this->beginTimer([__METHOD__]);
		
		$config->_boot_cache_hit = false;

		$data = null;
		if ($config->boot_cache_ttl > 0) {
			$data = $this->cache->load('boot_data');
		}

		if (!isset($data)) {
			$data = new BootData();
			$data->populate(_elgg_services()->entityTable, _elgg_services()->plugins, $installed);
			if ($config->boot_cache_ttl && $installed) {
				$this->cache->save('boot_data', $data, $config->boot_cache_ttl);
			}
		} else {
			$config->_boot_cache_hit = true;
		}

		$this->endTimer([__METHOD__]);
		
		return $data;
	}
}
