<?php
namespace Elgg\Database;


use Elgg\Database;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\EventsService as Events;
use ElggMetadata;
use ElggSession as Session;
use Elgg\Cache\MetadataCache as Cache;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class MetadataTable {

	use \Elgg\TimeUsing;

	/** @var Cache */
	protected $cache;

	/** @var Database */
	protected $db;

	/** @var EntityTable */
	protected $entityTable;

	/** @var Events */
	protected $events;

	/** @var Session */
	protected $session;

	/** @var string */
	protected $table;

	/**
	 * @var string[]
	 */
	protected $tag_names = [];

	const MYSQL_TEXT_BYTE_LIMIT = 65535;

	/**
	 * Constructor
	 *
	 * @param Cache       $cache       A cache for this table
	 * @param Database    $db          The Elgg database
	 * @param EntityTable $entityTable The entities table
	 * @param Events      $events      The events registry
	 * @param Session     $session     The session
	 */
	public function __construct(
			Cache $cache,
			Database $db,
			EntityTable $entityTable,
			Events $events,
			Session $session) {
		$this->cache = $cache;
		$this->db = $db;
		$this->entityTable = $entityTable;
		$this->events = $events;
		$this->session = $session;
		$this->table = $this->db->prefix . "metadata";
	}

	/**
	 * Registers a metadata name as containing tags for an entity.
	 *
	 * @param string $name Tag name
	 *
	 * @return bool
	 */
	function registerTagName($name) {
		if (!in_array($name, $this->tag_names)) {
			$this->tag_names[] = $name;
		}

		return true;
	}

	/**
	 * Returns an array of valid metadata names for tags.
	 *
	 * @return string[]
	 */
	function getTagNames() {
		return $this->tag_names;
	}

	/**
	 * Get a specific metadata object by its id.
	 * If you want multiple metadata objects, use
	 * {@link elgg_get_metadata()}.
	 *
	 * @param int $id The id of the metadata object being retrieved.
	 *
	 * @return ElggMetadata|false  false if not found
	 */
	function get($id) {
		$qb = Select::fromTable('metadata');
		$qb->select('*');

		$where = new MetadataWhereClause();
		$where->ids = $id;
		$qb->addClause($where);

		$row = $this->db->getDataRow($qb);
		if ($row) {
			return new ElggMetadata($row);
		}

		return false;
	}

	/**
	 * Deletes metadata using its ID.
	 *
	 * @param int $id The metadata ID to delete.
	 * @return bool
	 */
	function delete($id) {
		$metadata = $this->get($id);

		return $metadata ? $metadata->delete() : false;
	}

	/**
	 * Create a new metadata object, or update an existing one.
	 *
	 * Metadata can be an array by setting allow_multiple to true, but it is an
	 * indexed array with no control over the indexing.
	 *
	 * @param int    $entity_guid    The entity to attach the metadata to
	 * @param string $name           Name of the metadata
	 * @param string $value          Value of the metadata
	 * @param string $value_type     'text', 'integer', or '' for automatic detection
	 * @param bool   $allow_multiple Allow multiple values for one key. Default is false
	 *
	 * @return int|false id of metadata or false if failure
	 */
	function create($entity_guid, $name, $value, $value_type = '', $allow_multiple = false) {

		$entity_guid = (int) $entity_guid;
		$value_type = \ElggExtender::detectValueType($value, trim($value_type));
		$allow_multiple = (boolean) $allow_multiple;

		if (!isset($value)) {
			return false;
		}

		if (strlen($value) > self::MYSQL_TEXT_BYTE_LIMIT) {
			elgg_log("Metadata '$name' is above the MySQL TEXT size limit and may be truncated.", 'WARNING');
		}

		$query = "SELECT * FROM {$this->table}
			WHERE entity_guid = :entity_guid and name = :name LIMIT 1";

		$existing = $this->db->getDataRow($query, null, [
			':entity_guid' => $entity_guid,
			':name' => $name,
		]);
		if ($existing && !$allow_multiple) {
			$id = (int) $existing->id;
			$result = $this->update($id, $name, $value, $value_type);

			if (!$result) {
				return false;
			}
		} else {
			// Support boolean types
			if (is_bool($value)) {
				$value = (int) $value;
			}

			// If ok then add it
			$query = "INSERT INTO {$this->table}
				(entity_guid, name, value, value_type, time_created)
				VALUES (:entity_guid, :name, :value, :value_type, :time_created)";

			$id = $this->db->insertData($query, [
				':entity_guid' => $entity_guid,
				':name' => $name,
				':value' => $value,
				':value_type' => $value_type,
				':time_created' => $this->getCurrentTime()->getTimestamp(),
			]);

			if ($id !== false) {
				$obj = $this->get($id);
				if ($this->events->trigger('create', 'metadata', $obj)) {
					$this->cache->clear($entity_guid);

					return $id;
				} else {
					$this->delete($id);
				}
			}
		}

		return $id;
	}

	/**
	 * Update a specific piece of metadata.
	 *
	 * @param int    $id         ID of the metadata to update
	 * @param string $name       Metadata name
	 * @param string $value      Metadata value
	 * @param string $value_type Value type
	 *
	 * @return bool
	 */
	function update($id, $name, $value, $value_type) {
		$id = (int) $id;

		if (!$md = $this->get($id)) {
			return false;
		}
		if (!$md->canEdit()) {
			return false;
		}

		$value_type = \ElggExtender::detectValueType($value, trim($value_type));

		// Support boolean types (as integers)
		if (is_bool($value)) {
			$value = (int) $value;
		}
		if (strlen($value) > self::MYSQL_TEXT_BYTE_LIMIT) {
			elgg_log("Metadata '$name' is above the MySQL TEXT size limit and may be truncated.", 'WARNING');
		}
		// If ok then add it
		$query = "UPDATE {$this->table}
			SET name = :name,
			    value = :value,
				value_type = :value_type
			WHERE id = :id";

		$result = $this->db->updateData($query, false, [
			':name' => $name,
			':value' => $value,
			':value_type' => $value_type,
			':id' => $id,
		]);

		if ($result !== false) {
			$this->cache->clear($md->entity_guid);

			// @todo this event tells you the metadata has been updated, but does not
			// let you do anything about it. What is needed is a plugin hook before
			// the update that passes old and new values.
			$obj = $this->get($id);
			$this->events->trigger('update', 'metadata', $obj);
		}

		return $result;
	}

	/**
	 * Returns metadata.  Accepts all elgg_get_entities() options for entity
	 * restraints.
	 *
	 * @see elgg_get_entities
	 *
	 * @warning 1.7's find_metadata() didn't support limits and returned all metadata.
	 *          This function defaults to a limit of 25. There is probably not a reason
	 *          for you to return all metadata unless you're exporting an entity,
	 *          have other restraints in place, or are doing something horribly
	 *          wrong in your code.
	 *
	 * @param array $options Array in format:
	 *
	 * metadata_names               => null|ARR metadata names
	 * metadata_values              => null|ARR metadata values
	 * metadata_ids                 => null|ARR metadata ids
	 * metadata_case_sensitive      => BOOL Overall Case sensitive
	 * metadata_created_time_lower  => INT Lower limit for created time.
	 * metadata_created_time_upper  => INT Upper limit for created time.
	 * metadata_calculation         => STR Perform the MySQL function on the metadata values returned.
	 *                                   The "metadata_calculation" option causes this function to
	 *                                   return the result of performing a mathematical calculation on
	 *                                   all metadata that match the query instead of returning
	 *                                   \ElggMetadata objects.
	 *
	 * @return ElggMetadata[]|mixed
	 */
	function getAll(array $options = []) {

		$options['metastring_type'] = 'metadata';
		$options = _elgg_normalize_metastrings_options($options);

		return Metadata::find($options);
	}

	/**
	 * Deletes metadata based on $options.
	 *
	 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
	 *          This requires at least one constraint:
	 *          metadata_name(s), metadata_value(s), or guid(s) must be set.
	 *
	 * @param array $options An options array. {@link elgg_get_metadata()}
	 * @return bool|null true on success, false on failure, null if no metadata to delete.
	 */
	function deleteAll(array $options) {
		if (!_elgg_is_valid_options_for_batch_operation($options, 'metadata')) {
			return false;
		}

		// This moved last in case an object's constructor sets metadata. Currently the batch
		// delete process has to create the entity to delete its metadata. See #5214
		$this->cache->invalidateByOptions($options);

		$options['batch'] = true;
		$options['batch_size'] = 50;
		$options['batch_inc_offset'] = false;

		$metadata = Metadata::find($options);
		$count = $metadata->count();

		if (!$count) {
			return;
		}

		$success = 0;
		foreach ($metadata as $md) {
			if ($md->delete()) {
				$success++;
			}
		}

		return $success == $count;
	}

	/**
	 * Get the URL for this metadata
	 *
	 * By default this links to the export handler in the current view.
	 *
	 * @param int $id Metadata ID
	 *
	 * @return mixed
	 */
	function getUrl($id) {
		$extender = $this->get($id);

		return $extender ? $extender->getURL() : false;
	}
}
