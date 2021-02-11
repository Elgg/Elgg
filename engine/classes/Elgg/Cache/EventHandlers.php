<?php

namespace Elgg\Cache;

/**
 * Contains the cache event handlers
 *
 * @since 4.0
 */
class EventHandlers {
	
	/**
	 * Disables the caches in the system
	 *
	 * @return void
	 */
	public static function disable() {
		_elgg_services()->boot->getCache()->disable();
		_elgg_services()->plugins->getCache()->disable();
		_elgg_services()->sessionCache->disable();
		_elgg_services()->dataCache->disable();
		_elgg_services()->autoloadManager->getCache()->disable();
		_elgg_services()->systemCache->getCache()->disable();
		_elgg_services()->serverCache->getCache()->disable();
	}
	
	/**
	 * Enables the caches in the system
	 *
	 * @return void
	 */
	public static function enable() {
		_elgg_services()->boot->getCache()->enable();
		_elgg_services()->plugins->getCache()->enable();
		_elgg_services()->sessionCache->enable();
		_elgg_services()->dataCache->enable();
		_elgg_services()->autoloadManager->getCache()->enable();
		_elgg_services()->systemCache->getCache()->enable();
	}

	/**
	 * Purge the caches in the system
	 *
	 * @return void
	 */
	public static function purge() {
		_elgg_services()->boot->getCache()->purge();
		_elgg_services()->plugins->getCache()->purge();
		_elgg_services()->sessionCache->purge();
		_elgg_services()->dataCache->purge();
		_elgg_services()->simpleCache->purge();
		_elgg_services()->fileCache->purge();
		_elgg_services()->localFileCache->purge();
	}

	/**
	 * Invalidates the caches in the system
	 *
	 * @return void
	 */
	public static function invalidate() {
		_elgg_services()->boot->getCache()->invalidate();
		_elgg_services()->plugins->invalidate();
		_elgg_services()->sessionCache->invalidate();
		_elgg_services()->dataCache->invalidate();
		_elgg_services()->fileCache->invalidate();
		_elgg_services()->localFileCache->invalidate();
	}

	/**
	 * Invalidates the caches in the system
	 *
	 * @return void
	 */
	public static function clear() {
		_elgg_services()->boot->clearCache();
		_elgg_services()->plugins->clear();
		_elgg_services()->sessionCache->clear();
		_elgg_services()->dataCache->clear();
		_elgg_services()->simpleCache->clear();
		_elgg_services()->autoloadManager->deleteCache();
		_elgg_services()->fileCache->clear();
		_elgg_services()->localFileCache->clear();
		
		if (function_exists('opcache_reset')) {
			opcache_reset();
		}
	}
	
	/**
	 * Rebuild public service container
	 *
	 * @return void
	 */
	public static function rebuildPublicContainer() {
		$services = _elgg_services();
		$services->reset('dic_builder');
		$services->reset('dic');
	}
}
