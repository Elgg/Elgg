<?php
namespace Elgg\Database;


use Elgg\Database;
use Elgg\EventsService as Events;
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
	 * @return \ElggMetadata|false  false if not found
	 */
	function get($id) {
		return _elgg_get_metastring_based_object_from_id($id, 'metadata');
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
	 * @return \ElggMetadata[]|mixed
	 */
	function getAll(array $options = []) {

		// @todo remove support for count shortcut - see #4393
		// support shortcut of 'count' => true for 'metadata_calculation' => 'count'
		if (isset($options['count']) && $options['count']) {
			$options['metadata_calculation'] = 'count';
			unset($options['count']);
		}

		$options['metastring_type'] = 'metadata';
		return _elgg_get_metastring_based_objects($options);
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
		$options['metastring_type'] = 'metadata';
		$result = _elgg_batch_metastring_based_objects($options, 'elgg_batch_delete_callback', false);

		// This moved last in case an object's constructor sets metadata. Currently the batch
		// delete process has to create the entity to delete its metadata. See #5214
		$this->cache->invalidateByOptions($options);

		return $result;
	}

	/**
	 * Returns metadata name and value SQL where for entities.
	 * NB: $names and $values are not paired. Use $pairs for this.
	 * Pairs default to '=' operand.
	 *
	 * This function is reused for annotations because the tables are
	 * exactly the same.
	 *
	 * @param string     $e_table           Entities table name
	 * @param string     $n_table           Normalized metastrings table name (Where entities,
	 *                                    values, and names are joined. annotations / metadata)
	 * @param array|null $names             Array of names
	 * @param array|null $values            Array of values
	 * @param array|null $pairs             Array of names / values / operands
	 * @param string     $pair_operator     ("AND" or "OR") Operator to use to join the where clauses for pairs
	 * @param bool       $case_sensitive    Case sensitive metadata names?
	 * @param array|null $order_by_metadata Array of names / direction
	 *
	 * @return false|array False on fail, array('joins', 'wheres')
	 * @access private
	 */
	function getEntityMetadataWhereSql($e_table, $n_table, $names = null, $values = null,
			$pairs = null, $pair_operator = 'AND', $case_sensitive = true, $order_by_metadata = null) {
		// short circuit if nothing requested
		// 0 is a valid (if not ill-conceived) metadata name.
		// 0 is also a valid metadata value for false, null, or 0
		if ((!$names && $names !== 0)
			&& (!$values && $values !== 0)
			&& (!$pairs && $pairs !== 0)
			&& !$order_by_metadata) {
			return '';
		}

		// join counter for incremental joins.
		$i = 1;

		// binary forces byte-to-byte comparision of strings, making
		// it case- and diacritical-mark- sensitive.
		// only supported on values.
		$binary = ($case_sensitive) ? ' BINARY ' : '';

		$return =  [
			'joins' =>  [],
			'wheres' => [],
			'orders' => [],
		];

		$return['joins'][] = "JOIN {$this->db->prefix}{$n_table} n_table on
			{$e_table}.guid = n_table.entity_guid";

		$wheres = [];

		// get names wheres and joins
		$names_where = '';
		if ($names !== null) {
			if (!is_array($names)) {
				$names = [$names];
			}

			$sanitised_names = [];
			foreach ($names as $name) {
				// normalise to 0.
				if (!$name) {
					$name = '0';
				}
				$sanitised_names[] = '\'' . $this->db->sanitizeString($name) . '\'';
			}

			if ($names_str = implode(',', $sanitised_names)) {
				$names_where = "(n_table.name IN ($names_str))";
			}
		}

		// get values wheres and joins
		$values_where = '';
		if ($values !== null) {
			if (!is_array($values)) {
				$values = [$values];
			}

			$sanitised_values = [];
			foreach ($values as $value) {
				// normalize to 0
				if (!$value) {
					$value = 0;
				}
				$sanitised_values[] = '\'' . $this->db->sanitizeString($value) . '\'';
			}

			if ($values_str = implode(',', $sanitised_values)) {
				$values_where = "({$binary}n_table.value IN ($values_str))";
			}
		}

		if ($names_where && $values_where) {
			$wheres[] = "($names_where AND $values_where)";
		} elseif ($names_where) {
			$wheres[] = "($names_where)";
		} elseif ($values_where) {
			$wheres[] = "($values_where)";
		}

		// add pairs
		// pairs must be in arrays.
		if (is_array($pairs)) {
			// check if this is an array of pairs or just a single pair.
			if (isset($pairs['name']) || isset($pairs['value'])) {
				$pairs = [$pairs];
			}

			$pair_wheres = [];

			// @todo when the pairs are > 3 should probably split the query up to
			// denormalize the strings table.

			foreach ($pairs as $index => $pair) {
				// @todo move this elsewhere?
				// support shortcut 'n' => 'v' method.
				if (!is_array($pair)) {
					$pair = [
						'name' => $index,
						'value' => $pair
					];
				}

				// must have at least a name and value
				if (!isset($pair['name']) || !isset($pair['value'])) {
					// @todo should probably return false.
					continue;
				}

				// case sensitivity can be specified per pair.
				// default to higher level setting.
				if (isset($pair['case_sensitive'])) {
					$pair_binary = ($pair['case_sensitive']) ? ' BINARY ' : '';
				} else {
					$pair_binary = $binary;
				}

				if (isset($pair['operand'])) {
					$operand = $this->db->sanitizeString($pair['operand']);
				} else {
					$operand = ' = ';
				}

				// for comparing
				$trimmed_operand = trim(strtolower($operand));

				// certain operands can't work well with strings that can be interpreted as numbers
				// for direct comparisons like IN, =, != we treat them as strings
				// gt/lt comparisons need to stay unencapsulated because strings '5' > '15'
				// see https://github.com/Elgg/Elgg/issues/7009
				$num_safe_operands = ['>', '<', '>=', '<='];
				$num_test_operand = trim(strtoupper($operand));

				$value = '';
				if (is_numeric($pair['value']) && in_array($num_test_operand, $num_safe_operands)) {
					$value = $this->db->sanitizeString($pair['value']);
				} else if (is_bool($pair['value'])) {
					$value = (int) $pair['value'];
				} else if (is_array($pair['value'])) {
					$values_array = [];

					foreach ($pair['value'] as $pair_value) {
						if (is_numeric($pair_value) && !in_array($num_test_operand, $num_safe_operands)) {
							$values_array[] = $this->db->sanitizeString($pair_value);
						} else {
							$values_array[] = "'" . $this->db->sanitizeString($pair_value) . "'";
						}
					}

					if ($values_array) {
						$value = '(' . implode(', ', $values_array) . ')';
					}

					// @todo allow support for non IN operands with array of values.
					// will have to do more silly joins.
					$operand = 'IN';
				} else if ($trimmed_operand == 'in') {
					$value = "({$pair['value']})";
				} else {
					$value = "'" . $this->db->sanitizeString($pair['value']) . "'";
				}

				$name = $this->db->sanitizeString($pair['name']);

				$return['joins'][] = "JOIN {$this->db->prefix}{$n_table} n_table{$i}
					on {$e_table}.guid = n_table{$i}.entity_guid";

				$pair_wheres[] = "(n_table{$i}.name = '$name' AND {$pair_binary}n_table{$i}.value
					$operand $value)";

				$i++;
			}

			if ($where = implode(" $pair_operator ", $pair_wheres)) {
				$wheres[] = "($where)";
			}
		}

		if ($where = implode(' AND ', $wheres)) {
			$return['wheres'][] = "($where)";
		}

		if (is_array($order_by_metadata)) {
			if ((count($order_by_metadata) > 0) && !isset($order_by_metadata[0])) {
				// singleton, so fix
				$order_by_metadata = [$order_by_metadata];
			}
			foreach ($order_by_metadata as $order_by) {
				if (is_array($order_by) && isset($order_by['name'])) {
					$name = $this->db->sanitizeString($order_by['name']);
					if (isset($order_by['direction'])) {
						$direction = $this->db->sanitizeString($order_by['direction']);
					} else {
						$direction = 'ASC';
					}
					$return['joins'][] = "JOIN {$this->db->prefix}{$n_table} n_table{$i}
						on {$e_table}.guid = n_table{$i}.entity_guid";

					$return['wheres'][] = "(n_table{$i}.name = '$name')";

					if (isset($order_by['as']) && $order_by['as'] == 'integer') {
						$return['orders'][] = "CAST(n_table{$i}.value AS SIGNED) $direction";
					} else {
						$return['orders'][] = "n_table{$i}.value $direction";
					}
					$i++;
				}
			}
		}

		return $return;
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
