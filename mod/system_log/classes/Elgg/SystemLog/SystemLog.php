<?php

namespace Elgg\SystemLog;

use Elgg\Application\Database;
use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Traits\Di\ServiceFacade;
use Elgg\Traits\TimeUsing;

/**
 * Inserts log entry into the database
 */
class SystemLog {

	use TimeUsing;
	use ServiceFacade;

	/**
	 * @var LogEventCache
	 */
	protected $cache;

	/**
	 * @var Database
	 */
	protected $db;
	
	/**
	 * @var bool
	 */
	protected $logging_enabled = true;

	/**
	 * Constructor
	 *
	 * @param LogEventCache $cache Log events cache
	 * @param Database      $db    Database
	 */
	public function __construct(LogEventCache $cache, Database $db) {
		$this->cache = $cache;
		$this->db = $db;
	}

	/**
	 * Get system log entry from its ID
	 *
	 * @param int $id ID
	 *
	 * @return SystemLogEntry|false
	 */
	public function get($id) {
		$qb = Select::fromTable('system_log');
		$qb->select('*');
		$qb->where($qb->compare('id', '=', $id, ELGG_VALUE_INTEGER));

		return $this->db->getDataRow($qb, [$this, 'rowToSystemLogEntry']);
	}

	/**
	 * Retrieve the system log based on a number of parameters.
	 *
	 * @param array $options Options
	 *
	 * @option int       $limit             Maximum number of responses to return. (default from settings)
	 * @option int       $offset            Offset of where to start.
	 * @option bool      $count             Return count or not
	 * @option int|array $performed_by_guid The guid(s) of the user(s) who initiated the event.
	 * @option string    $event             The event you are searching on.
	 * @option string    $object_class      The class of object it effects.
	 * @option string    $object_type       The type
	 * @option string    $object_subtype    The subtype.
	 * @option int       $object_id         GUID of an object
	 * @option int       $created_before    Lower time limit
	 * @option int       $created_after     Upper time limit
	 * @option string    $ip_address        The IP address.
	 *
	 * @return int|\stdClass[]
	 */
	public function getAll(array $options = []) {
		$query = new SystemLogQuery();
		foreach ($options as $key => $value) {
			$query->$key = $value;
		}

		$query->setCallback([$this, 'rowToSystemLogEntry']);

		return $query->execute();
	}

	/**
	 * Construct log entry object from DB row
	 *
	 * @param \stdClass $row DB row
	 *
	 * @return SystemLogEntry
	 */
	public function rowToSystemLogEntry(\stdClass $row): SystemLogEntry {
		return new SystemLogEntry($row);
	}

	/**
	 * Prepare an object for DB insert
	 *
	 * @param \Loggable $object Object
	 *
	 * @return \stdClass
	 */
	protected function prepareObjectForInsert(\Loggable $object): \stdClass {
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
	public function insert($object, $event): void {

		if (!$this->isLoggingEnabled()) {
			return;
		}
		
		if (!$object instanceof \Loggable) {
			return;
		}

		$object = $this->prepareObjectForInsert($object);

		$logged = $this->cache->load("{$object->object_id}/{$event}");

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
			'time_created' => $qb->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_INTEGER),
			'ip_address' => $qb->param($object->ip_address, ELGG_VALUE_STRING),
		]);

		$this->db->registerDelayedQuery($qb, 'write');

		// The only purpose of the cache is to prevent the same event from writing to the database twice
		// Setting early expiration to avoid cache from taking up too much memory
		$this->cache->save("{$object->object_id}/{$event}", $object, 3600);
	}

	/**
	 * Archive records created before a date
	 *
	 * @param \DateTime $created_before Date of last creation
	 *
	 * @return bool
	 */
	public function archive(\DateTime $created_before): bool {

		$dbprefix = $this->db->prefix;

		$now = $this->getCurrentTime()->getTimestamp();

		$select = Select::fromTable('system_log');
		$select->select('*')
			->where($select->compare('time_created', '<=', $created_before, ELGG_VALUE_TIMESTAMP));

		$query = "CREATE TABLE {$dbprefix}system_log_{$now} AS {$select->getSQL()}";
		
		if (!$this->db->getConnection('write')->executeStatement($query, $select->getParameters())) {
			return false;
		}

		// delete
		$delete = Delete::fromTable('system_log');
		$delete->where($delete->compare('time_created', '<=', $created_before, ELGG_VALUE_TIMESTAMP));

		// Don't delete on time since we are running in a concurrent environment
		if ($this->db->deleteData($delete) === false) {
			return false;
		}

		// alter table to archive engine (when available)
		$available_engines_result = $this->db->getConnection('read')->executeQuery('SHOW ENGINES');
		$available_engines = array_filter($available_engines_result->fetchAllAssociative(), function($row_array) {
			// filter only enabled engines
			return in_array($row_array['Support'], ['YES', 'DEFAULT']);
		});
		array_walk($available_engines, function(&$row_array) {
			// only need engine names
			$row_array = $row_array['Engine'];
		});
		
		if (in_array('ARCHIVE', $available_engines)) {
			try {
				$this->db->getConnection('write')->executeStatement("ALTER TABLE {$dbprefix}system_log_{$now} ENGINE=ARCHIVE");
			} catch (\Exception $e) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Deleted system log archive tables
	 *
	 * @param \DateTime $archived_before Date of last archival
	 *
	 * @return bool
	 */
	public function deleteArchive(\DateTime $archived_before): bool {

		$dbprefix = $this->db->prefix;

		$deleted_tables = false;

		$results = $this->db->getConnection('read')->executeQuery("SHOW TABLES like '{$dbprefix}system_log_%'");
		
		if (empty($results) || empty($results->rowCount())) {
			return $deleted_tables;
		}

		foreach ($results->fetchAllAssociative() as $result) {
			$table_name = array_shift($result);

			// extract log table rotation time
			$log_time = (int) str_replace("{$dbprefix}system_log_", '', $table_name);

			if ($log_time <= $archived_before->getTimestamp()) {
				try {
					$this->db->getConnection('write')->executeStatement("DROP TABLE {$table_name}");
					$deleted_tables = true;
				} catch (\Exception $e) {
					elgg_log("Failed to delete the log table {$table_name}", 'ERROR');
				}
			}
		}

		return $deleted_tables;
	}

	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'system_log';
	}
	
	/**
	 * Enable the logging of system events
	 *
	 * @return void
	 */
	public function enableLogging(): void {
		$this->logging_enabled = true;
	}
	
	/**
	 * Disable the logging of system events
	 *
	 * @return void
	 */
	public function disableLogging(): void {
		$this->logging_enabled = false;
	}
	
	/**
	 * Is logging currently enabled
	 *
	 * @return bool
	 */
	public function isLoggingEnabled(): bool {
		return $this->logging_enabled;
	}
}
