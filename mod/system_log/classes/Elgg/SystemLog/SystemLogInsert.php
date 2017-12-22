<?php

namespace Elgg\SystemLog;

use Elgg\Cache\CompositeCache;
use Elgg\Database\Insert;

/**
 * Inserts log entry into the database
 */
class SystemLogInsert {

	/**
	 * @var CompositeCache
	 */
	protected static $cache;

	/**
	 * Returns a cache of logged system events
	 *
	 * @return CompositeCache
	 */
	protected function getCache() {
		if (!isset(self::$cache)) {
			$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
			$cache = new CompositeCache('system_log', _elgg_config(), $flags);

			register_shutdown_function(function () use ($cache) {
				$cache->clear();
			});

			self::$cache = $cache;
		}

		return self::$cache;
	}

	/**
	 * Prepare an object for DB insert
	 *
	 * @param \Loggable $object Object
	 *
	 * @return \stdClass
	 */
	protected function prepareObjectForInsert(\Loggable $object) {
		$insert = new \stdClass();

		$insert->object_id = (int) $object->getSystemLogID();
		$insert->object_class = get_class($object);
		$insert->object_type = $object->getType();
		$insert->object_subtype = $object->getSubtype();
		$insert->ip_address = _elgg_services()->request->getClientIp() ? : '0.0.0.0';
		$insert->performed_by_guid = elgg_get_logged_in_user_guid();

		if (isset($object->access_id)) {
			$insert->access_id = $object->access_id;
		} else {
			$insert->access_id = ACCESS_PUBLIC;
		}

		if (isset($object->enabled)) {
			$insert->enabled = $object->enabled;
		} else {
			$insert->enabled = 'yes';
		}

		if (isset($object->owner_guid)) {
			$insert->owner_guid = $object->owner_guid;
		} else {
			$insert->owner_guid = 0;
		}

		return $insert;
	}


	/**
	 * Logs a system event in the database
	 *
	 * @param \stdClass $object Object to log
	 * @param string    $event  Event name
	 *
	 * @return void
	 */
	public function insert($object, $event) {

		if (!$object instanceof \Loggable) {
			return;
		}

		$object = $this->prepareObjectForInsert($object);

		$logged = $this->getCache()->load("$object->object_id/$event");

		if ($logged == $object) {
			return;
		}

		$qb = Insert::intoTable('system_log');
		$qb->values([
			'object_id' => $qb->param($object->object_id, ELGG_VALUE_INTEGER),
			'object_class' => $qb->param($object->object_class, ELGG_VALUE_STRING),
			'object_type' => $qb->param($object->object_type, ELGG_VALUE_STRING),
			'object_subtype' => $qb->param($object->object_subtype, ELGG_VALUE_STRING),
			'event' => $qb->param($event, ELGG_VALUE_STRING),
			'performed_by_guid' => $qb->param($object->performed_by_guid, ELGG_VALUE_INTEGER),
			'owner_guid' => $qb->param($object->owner_guid, ELGG_VALUE_INTEGER),
			'access_id' => $qb->param($object->access_id, ELGG_VALUE_INTEGER),
			'enabled' => $qb->param($object->enabled, ELGG_VALUE_STRING),
			'time_created' => $qb->param(time(), ELGG_VALUE_INTEGER),
			'ip_address' => $qb->param('ip_address', ELGG_VALUE_STRING),
		]);

		execute_delayed_write_query($qb->getSQL(), null, $qb->getParameters());

		// The only purpose of the cache is to prevent the same event from writing to the database twice
		// Setting early expiration to avoid cache from taking up too much memory
		$this->getCache()->save("$object->object_id/$event", $object, 3600);
	}

}