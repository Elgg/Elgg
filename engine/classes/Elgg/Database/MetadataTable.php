<?php
namespace Elgg\Database;


use Elgg\Database;
use Elgg\Database\EntityTable;
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

	/** @var array */
	protected $independents = [];
	
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
	 * @param int    $owner_guid     GUID of entity that owns the metadata. Default is logged in user.
	 * @param int    $ignored        This argument is not used
	 * @param bool   $allow_multiple Allow multiple values for one key. Default is false
	 *
	 * @return int|false id of metadata or false if failure
	 */
	function create($entity_guid, $name, $value, $value_type = '', $owner_guid = 0,
			$ignored = ACCESS_PRIVATE, $allow_multiple = false) {

		$entity_guid = (int) $entity_guid;
		$value_type = \ElggExtender::detectValueType($value, trim($value_type));
		$owner_guid = (int) $owner_guid;
		$allow_multiple = (boolean) $allow_multiple;

		if (!isset($value)) {
			return false;
		}
	
		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}

		$access_id = ACCESS_PUBLIC;

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
			$result = $this->update($id, $name, $value, $value_type, $owner_guid);

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
				(entity_guid, name, value, value_type, owner_guid, time_created, access_id)
				VALUES (:entity_guid, :name, :value, :value_type, :owner_guid, :time_created, :access_id)";
			
			$id = $this->db->insertData($query, [
				':entity_guid' => $entity_guid,
				':name' => $name,
				':value' => $value,
				':value_type' => $value_type,
				':owner_guid' => (int) $owner_guid,
				':time_created' => $this->getCurrentTime()->getTimestamp(),
				':access_id' => $access_id,
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
	 * @param int    $owner_guid Owner guid
	 *
	 * @return bool
	 */
	function update($id, $name, $value, $value_type, $owner_guid) {
		$id = (int) $id;

		if (!$md = $this->get($id)) {
			return false;
		}
		if (!$md->canEdit()) {
			return false;
		}
	
		$value_type = \ElggExtender::detectValueType($value, trim($value_type));
	
		$owner_guid = (int) $owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}
	
		$access_id = ACCESS_PUBLIC;

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
				value_type = :value_type,
				access_id = :access_id,
			    owner_guid = :owner_guid
			WHERE id = :id";
		
		$result = $this->db->updateData($query, false, [
			':name' => $name,
			':value' => $value,
			':value_type' => $value_type,
			':access_id' => $access_id,
			':owner_guid' => $owner_guid,
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
	 * This function creates metadata from an associative array of "key => value" pairs.
	 *
	 * To achieve an array for a single key, pass in the same key multiple times with
	 * allow_multiple set to true. This creates an indexed array. It does not support
	 * associative arrays and there is no guarantee on the ordering in the array.
	 *
	 * @param int    $entity_guid     The entity to attach the metadata to
	 * @param array  $name_and_values Associative array - a value can be a string, number, bool
	 * @param string $value_type      'text', 'integer', or '' for automatic detection
	 * @param int    $owner_guid      GUID of entity that owns the metadata
	 * @param int    $ignored         This argument is not used
	 * @param bool   $allow_multiple  Allow multiple values for one key. Default is false
	 *
	 * @return bool
	 */
	function createFromArray($entity_guid, array $name_and_values, $value_type, $owner_guid,
			$ignored = ACCESS_PRIVATE, $allow_multiple = false) {
	
		foreach ($name_and_values as $k => $v) {
			$result = $this->create($entity_guid, $k, $v, $value_type, $owner_guid,
				null, $allow_multiple);
			if (!$result) {
				return false;
			}
		}
		return true;
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
	 * metadata_owner_guids         => null|ARR guids for metadata owners
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
	 *          This requires at least one constraint: metadata_owner_guid(s),
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
	 * Disables metadata based on $options.
	 *
	 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
	 *
	 * @param array $options An options array. {@link elgg_get_metadata()}
	 * @return bool|null true on success, false on failure, null if no metadata disabled.
	 */
	function disableAll(array $options) {
		if (!_elgg_is_valid_options_for_batch_operation($options, 'metadata')) {
			return false;
		}
	
		$this->cache->invalidateByOptions($options);
	
		// if we can see hidden (disabled) we need to use the offset
		// otherwise we risk an infinite loop if there are more than 50
		$inc_offset = access_get_show_hidden_status();
	
		$options['metastring_type'] = 'metadata';
		return _elgg_batch_metastring_based_objects($options, 'elgg_batch_disable_callback', $inc_offset);
	}
	
	/**
	 * Enables metadata based on $options.
	 *
	 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
	 *
	 * @warning In order to enable metadata, you must first use
	 * {@link access_show_hidden_entities()}.
	 *
	 * @param array $options An options array. {@link elgg_get_metadata()}
	 * @return bool|null true on success, false on failure, null if no metadata enabled.
	 */
	function enableAll(array $options) {
		if (!$options || !is_array($options)) {
			return false;
		}
	
		$this->cache->invalidateByOptions($options);
	
		$options['metastring_type'] = 'metadata';
		return _elgg_batch_metastring_based_objects($options, 'elgg_batch_enable_callback');
	}
	
	/**
	 * Returns entities based upon metadata.  Also accepts all
	 * options available to elgg_get_entities().  Supports
	 * the singular option shortcut.
	 *
	 * @note Using metadata_names and metadata_values results in a
	 * "names IN (...) AND values IN (...)" clause.  This is subtly
	 * differently than default multiple metadata_name_value_pairs, which use
	 * "(name = value) AND (name = value)" clauses.
	 *
	 * When in doubt, use name_value_pairs.
	 *
	 * To ask for entities that do not have a metadata value, use a custom
	 * where clause like this:
	 *
	 * 	$options['wheres'][] = "NOT EXISTS (
	 *			SELECT 1 FROM {$dbprefix}metadata md
	 *			WHERE md.entity_guid = e.guid
	 *				AND md.name = $name
	 *				AND md.value = $value)";
	 *
	 * Note the metadata name and value has been denormalized in the above example.
	 *
	 * @see elgg_get_entities
	 *
	 * @param array $options Array in format:
	 *
	 * 	metadata_names => null|ARR metadata names
	 *
	 * 	metadata_values => null|ARR metadata values
	 *
	 * 	metadata_name_value_pairs => null|ARR (
	 *                                         name => 'name',
	 *                                         value => 'value',
	 *                                         'operand' => '=',
	 *                                         'case_sensitive' => true
	 *                                        )
	 *                               Currently if multiple values are sent via
	 *                               an array (value => array('value1', 'value2')
	 *                               the pair's operand will be forced to "IN".
	 *                               If passing "IN" as the operand and a string as the value,
	 *                               the value must be a properly quoted and escaped string.
	 *
	 * 	metadata_name_value_pairs_operator => null|STR The operator to use for combining
	 *                                        (name = value) OPERATOR (name = value); default AND
	 *
	 * 	metadata_case_sensitive => BOOL Overall Case sensitive
	 *
	 *  order_by_metadata => null|ARR array(
	 *                                      'name' => 'metadata_text1',
	 *                                      'direction' => ASC|DESC,
	 *                                      'as' => text|integer
	 *                                     )
	 *                                Also supports array('name' => 'metadata_text1')
	 *
	 *  metadata_owner_guids => null|ARR guids for metadata owners
	 *
	 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
	 */
	function getEntities(array $options = []) {
		$defaults = [
			'metadata_names'                     => ELGG_ENTITIES_ANY_VALUE,
			'metadata_values'                    => ELGG_ENTITIES_ANY_VALUE,
			'metadata_name_value_pairs'          => ELGG_ENTITIES_ANY_VALUE,
	
			'metadata_name_value_pairs_operator' => 'AND',
			'metadata_case_sensitive'            => true,
			'order_by_metadata'                  => [],
	
			'metadata_owner_guids'               => ELGG_ENTITIES_ANY_VALUE,
		];
	
		$options = array_merge($defaults, $options);
	
		$singulars = ['metadata_name', 'metadata_value',
			'metadata_name_value_pair', 'metadata_owner_guid'];
	
		$options = _elgg_normalize_plural_options_array($options, $singulars);
	
		if (!$options = _elgg_entities_get_metastrings_options('metadata', $options)) {
			return false;
		}
	
		return $this->entityTable->getEntities($options);
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
	 * @param array|null $owner_guids       Array of owner GUIDs
	 *
	 * @return false|array False on fail, array('joins', 'wheres')
	 * @access private
	 */
	function getEntityMetadataWhereSql($e_table, $n_table, $names = null, $values = null,
			$pairs = null, $pair_operator = 'AND', $case_sensitive = true, $order_by_metadata = null,
			$owner_guids = null) {
		// short circuit if nothing requested
		// 0 is a valid (if not ill-conceived) metadata name.
		// 0 is also a valid metadata value for false, null, or 0
		// 0 is also a valid(ish) owner_guid
		if ((!$names && $names !== 0)
			&& (!$values && $values !== 0)
			&& (!$pairs && $pairs !== 0)
			&& (!$owner_guids && $owner_guids !== 0)
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
	
		// add owner_guids
		if ($owner_guids) {
			if (is_array($owner_guids)) {
				$sanitised = array_map('sanitise_int', $owner_guids);
				$owner_str = implode(',', $sanitised);
			} else {
				$owner_str = (int) $owner_guids;
			}
	
			$wheres[] = "(n_table.owner_guid IN ($owner_str))";
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
	
	/**
	 * Mark entities with a particular type and subtype as having access permissions
	 * that can be changed independently from their parent entity
	 *
	 * @param string $type    The type - object, user, etc
	 * @param string $subtype The subtype; all subtypes by default
	 *
	 * @return void
	 */
	function registerMetadataAsIndependent($type, $subtype = '*') {
		if (!isset($this->independents[$type])) {
			$this->independents[$type] = [];
		}
		
		$this->independents[$type][$subtype] = true;
	}
	
	/**
	 * Determines whether entities of a given type and subtype should not change
	 * their metadata in line with their parent entity
	 *
	 * @param string $type    The type - object, user, etc
	 * @param string $subtype The entity subtype
	 *
	 * @return bool
	 */
	function isMetadataIndependent($type, $subtype) {
		if (empty($this->independents[$type])) {
			return false;
		}

		return !empty($this->independents[$type][$subtype])
			|| !empty($this->independents[$type]['*']);
	}
}
