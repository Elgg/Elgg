<?php
namespace Elgg\Database;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class Annotations {
	/**
	 * Global Elgg configuration
	 * 
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
	}

	/**
	 * Get a specific annotation by its id.
	 * If you want multiple annotation objects, use
	 * {@link elgg_get_annotations()}.
	 *
	 * @param int $id The id of the annotation object being retrieved.
	 *
	 * @return \ElggAnnotation|false
	 */
	function get($id) {
		return _elgg_get_metastring_based_object_from_id($id, 'annotation');
	}
	
	/**
	 * Deletes an annotation using its ID.
	 *
	 * @param int $id The annotation ID to delete.
	 * @return bool
	 */
	function delete($id) {
		$annotation = elgg_get_annotation_from_id($id);
		if (!$annotation) {
			return false;
		}
		return $annotation->delete();
	}
	
	/**
	 * Create a new annotation.
	 *
	 * @param int    $entity_guid GUID of entity to be annotated
	 * @param string $name        Name of annotation
	 * @param string $value       Value of annotation
	 * @param string $value_type  Type of value (default is auto detection)
	 * @param int    $owner_guid  Owner of annotation (default is logged in user)
	 * @param int    $access_id   Access level of annotation
	 *
	 * @return int|bool id on success or false on failure
	 */
	function create($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $access_id = ACCESS_PRIVATE) {
		
	
		$result = false;
	
		$entity_guid = (int)$entity_guid;
		$value_type = detect_extender_valuetype($value, $value_type);
	
		$owner_guid = (int)$owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
	
		$access_id = (int)$access_id;
		$time = time();
	
		$value_id = elgg_get_metastring_id($value);
		if (!$value_id) {
			return false;
		}
	
		$name_id = elgg_get_metastring_id($name);
		if (!$name_id) {
			return false;
		}
	
		// @todo we don't check that the entity is loaded which means the user may
		// not have access to the entity
		$entity = get_entity($entity_guid);
	
		if (_elgg_services()->events->trigger('annotate', $entity->type, $entity)) {
			$result = _elgg_services()->db->insertData("INSERT INTO {$this->CONFIG->dbprefix}annotations
				(entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id) VALUES
				($entity_guid, $name_id, $value_id, '$value_type', $owner_guid, $time, $access_id)");
	
			if ($result !== false) {
				$obj = elgg_get_annotation_from_id($result);
				if (_elgg_services()->events->trigger('create', 'annotation', $obj)) {
					return $result;
				} else {
					// plugin returned false to reject annotation
					elgg_delete_annotation_by_id($result);
					return false;
				}
			}
		}
	
		return $result;
	}
	
	/**
	 * Update an annotation.
	 *
	 * @param int    $annotation_id Annotation ID
	 * @param string $name          Name of annotation
	 * @param string $value         Value of annotation
	 * @param string $value_type    Type of value
	 * @param int    $owner_guid    Owner of annotation
	 * @param int    $access_id     Access level of annotation
	 *
	 * @return bool
	 */
	function update($annotation_id, $name, $value, $value_type, $owner_guid, $access_id) {
		
	
		$annotation_id = (int)$annotation_id;
	
		$annotation = elgg_get_annotation_from_id($annotation_id);
		if (!$annotation) {
			return false;
		}
		if (!$annotation->canEdit()) {
			return false;
		}
	
		$name = trim($name);
		$value_type = detect_extender_valuetype($value, $value_type);
	
		$owner_guid = (int)$owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
	
		$access_id = (int)$access_id;
	
		$value_id = elgg_get_metastring_id($value);
		if (!$value_id) {
			return false;
		}
	
		$name_id = elgg_get_metastring_id($name);
		if (!$name_id) {
			return false;
		}
	
		$result = _elgg_services()->db->updateData("UPDATE {$this->CONFIG->dbprefix}annotations
			SET name_id = $name_id, value_id = $value_id, value_type = '$value_type',
			access_id = $access_id, owner_guid = $owner_guid
			WHERE id = $annotation_id");
	
		if ($result !== false) {
			// @todo add plugin hook that sends old and new annotation information before db access
			$obj = elgg_get_annotation_from_id($annotation_id);
			_elgg_services()->events->trigger('update', 'annotation', $obj);
		}
	
		return $result;
	}
	
	/**
	 * Returns annotations.  Accepts all elgg_get_entities() options for entity
	 * restraints.
	 *
	 * @see elgg_get_entities
	 *
	 * @param array $options Array in format:
	 *
	 * annotation_names              => null|ARR Annotation names
	 * annotation_values             => null|ARR Annotation values
	 * annotation_ids                => null|ARR annotation ids
	 * annotation_case_sensitive     => BOOL Overall Case sensitive
	 * annotation_owner_guids        => null|ARR guids for annotation owners
	 * annotation_created_time_lower => INT Lower limit for created time.
	 * annotation_created_time_upper => INT Upper limit for created time.
	 * annotation_calculation        => STR Perform the MySQL function on the annotation values returned.
	 *                                   Do not confuse this "annotation_calculation" option with the
	 *                                   "calculation" option to elgg_get_entities_from_annotation_calculation().
	 *                                   The "annotation_calculation" option causes this function to
	 *                                   return the result of performing a mathematical calculation on
	 *                                   all annotations that match the query instead of \ElggAnnotation
	 *                                   objects.
	 *                                   See the docs for elgg_get_entities_from_annotation_calculation()
	 *                                   for the proper use of the "calculation" option.
	 *
	 *
	 * @return \ElggAnnotation[]|mixed
	 */
	function find(array $options = array()) {

		// support shortcut of 'count' => true for 'annotation_calculation' => 'count'
		if (isset($options['count']) && $options['count']) {
			$options['annotation_calculation'] = 'count';
			unset($options['count']);
		}
		
		$options['metastring_type'] = 'annotations';
		return _elgg_get_metastring_based_objects($options);
	}
	
	/**
	 * Deletes annotations based on $options.
	 *
	 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
	 *          This requires at least one constraint: annotation_owner_guid(s),
	 *          annotation_name(s), annotation_value(s), or guid(s) must be set.
	 *
	 * @param array $options An options array. {@link elgg_get_annotations()}
	 * @return bool|null true on success, false on failure, null if no annotations to delete.
	 */
	function deleteAll(array $options) {
		if (!_elgg_is_valid_options_for_batch_operation($options, 'annotation')) {
			return false;
		}
	
		$options['metastring_type'] = 'annotations';
		return _elgg_batch_metastring_based_objects($options, 'elgg_batch_delete_callback', false);
	}
	
	/**
	 * Disables annotations based on $options.
	 *
	 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
	 *
	 * @param array $options An options array. {@link elgg_get_annotations()}
	 * @return bool|null true on success, false on failure, null if no annotations disabled.
	 */
	function disableAll(array $options) {
		if (!_elgg_is_valid_options_for_batch_operation($options, 'annotation')) {
			return false;
		}
		
		// if we can see hidden (disabled) we need to use the offset
		// otherwise we risk an infinite loop if there are more than 50
		$inc_offset = access_get_show_hidden_status();
	
		$options['metastring_type'] = 'annotations';
		return _elgg_batch_metastring_based_objects($options, 'elgg_batch_disable_callback', $inc_offset);
	}
	
	/**
	 * Enables annotations based on $options.
	 *
	 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
	 *
	 * @warning In order to enable annotations, you must first use
	 * {@link access_show_hidden_entities()}.
	 *
	 * @param array $options An options array. {@link elgg_get_annotations()}
	 * @return bool|null true on success, false on failure, null if no metadata enabled.
	 */
	function enableAll(array $options) {
		if (!$options || !is_array($options)) {
			return false;
		}
	
		$options['metastring_type'] = 'annotations';
		return _elgg_batch_metastring_based_objects($options, 'elgg_batch_enable_callback');
	}
	
	/**
	 * Returns entities based upon annotations.  Also accepts all options available
	 * to elgg_get_entities() and elgg_get_entities_from_metadata().
	 *
	 * @see elgg_get_entities
	 * @see elgg_get_entities_from_metadata
	 *
	 * @param array $options Array in format:
	 *
	 * 	annotation_names => null|ARR annotations names
	 *
	 * 	annotation_values => null|ARR annotations values
	 *
	 * 	annotation_name_value_pairs => null|ARR (name = 'name', value => 'value',
	 * 	'operator' => '=', 'case_sensitive' => true) entries.
	 * 	Currently if multiple values are sent via an array (value => array('value1', 'value2')
	 * 	the pair's operator will be forced to "IN".
	 *
	 * 	annotation_name_value_pairs_operator => null|STR The operator to use for combining
	 *  (name = value) OPERATOR (name = value); default AND
	 *
	 * 	annotation_case_sensitive => BOOL Overall Case sensitive
	 *
	 *  order_by_annotation => null|ARR (array('name' => 'annotation_text1', 'direction' => ASC|DESC,
	 *  'as' => text|integer),
	 *
	 *  Also supports array('name' => 'annotation_text1')
	 *
	 *  annotation_owner_guids => null|ARR guids for annotaiton owners
	 *
	 * @return mixed If count, int. If not count, array. false on errors.
	 */
	function getEntities(array $options = array()) {
		$defaults = array(
			'annotation_names'						=>	ELGG_ENTITIES_ANY_VALUE,
			'annotation_values'						=>	ELGG_ENTITIES_ANY_VALUE,
			'annotation_name_value_pairs'			=>	ELGG_ENTITIES_ANY_VALUE,
	
			'annotation_name_value_pairs_operator'	=>	'AND',
			'annotation_case_sensitive' 			=>	true,
			'order_by_annotation'					=>	array(),
	
			'annotation_created_time_lower'			=>	ELGG_ENTITIES_ANY_VALUE,
			'annotation_created_time_upper'			=>	ELGG_ENTITIES_ANY_VALUE,
	
			'annotation_owner_guids'				=>	ELGG_ENTITIES_ANY_VALUE,
		);
	
		$options = array_merge($defaults, $options);
	
		$singulars = array('annotation_name', 'annotation_value',
		'annotation_name_value_pair', 'annotation_owner_guid');
	
		$options = _elgg_normalize_plural_options_array($options, $singulars);
		$options = _elgg_entities_get_metastrings_options('annotation', $options);
	
		if (!$options) {
			return false;
		}
	
		// because of performance issues support for ordering by maxtime has been dropped
		// @see https://github.com/Elgg/Elgg/issues/6638
		if (isset($options['order_by']) && preg_match('~\bmaxtime\b~i', $options['order_by'])) {
			// check if the user provided maxtime
			$deprecated = true;
			if (isset($options['selects'])) {
				$selects = $options['selects'];
				if (!is_array($selects)) {
					$selects = array($selects);
				}
					
				foreach ($selects as $select) {
					if (preg_match('~\bmaxtime\b~i', $options['order_by'])) {
						$deprecated = false;
						break;
					}
				}
			}
		
			// the user didn't provide maxtime
			if ($deprecated) {
				// special sorting for annotations
				elgg_deprecated_notice(__FUNCTION__ . ": no longer orders by annotations by default. If you order"
					. " by maxtime, you must provide that column via \$options['selects']. See"
					. " https://github.com/Elgg/Elgg/issues/6638#issuecomment-41562034", "1.10");
					
				$options['selects'][] = "MAX(n_table.time_created) AS maxtime";
				$options['group_by'] = 'n_table.entity_guid';
			}
		}
		
		$time_wheres = _elgg_get_entity_time_where_sql('a', $options['annotation_created_time_upper'],
			$options['annotation_created_time_lower']);
	
		if ($time_wheres) {
			$options['wheres'] = array_merge($options['wheres'], $time_wheres);
		}
	
		return elgg_get_entities_from_metadata($options);
	}
	
	/**
	 * Get entities ordered by a mathematical calculation on annotation values
	 *
	 * @tip Note that this function uses { @link elgg_get_annotations() } to return a list of entities ordered by a mathematical
	 * calculation on annotation values, and { @link elgg_get_entities_from_annotations() } to return a count of entities
	 * if $options['count'] is set to a truthy value
	 *
	 * @param array $options An options array:
	 * 	'calculation'            => The calculation to use. Must be a valid MySQL function.
	 *                              Defaults to sum.  Result selected as 'annotation_calculation'.
	 *                              Don't confuse this "calculation" option with the
	 *                              "annotation_calculation" option to elgg_get_annotations().
	 *                              This "calculation" option is applied to each entity's set of
	 *                              annotations and is selected as annotation_calculation for that row.
	 *                              See the docs for elgg_get_annotations() for proper use of the
	 *                              "annotation_calculation" option.
	 *	'order_by'               => The order for the sorting. Defaults to 'annotation_calculation desc'.
	 *	'annotation_names'       => The names of annotations on the entity.
	 *	'annotation_values'	     => The values of annotations on the entity.
	 *
	 * 	'metadata_names'         => The name of metadata on the entity.
	 * 	'metadata_values'        => The value of metadata on the entitiy.
	 * 	'callback'               => Callback function to pass each row through.
	 *                              @tip This function is different from other ege* functions, 
	 *                              as it uses a metastring-based getter function { @link elgg_get_annotations() },
	 *                              therefore the callback function should be a derivative of { @link entity_row_to_elggstar() }
	 *                              and not of { @link row_to_annotation() }
	 *
	 * @return \ElggEntity[]|int An array or a count of entities
	 * @see elgg_get_annotations()
	 * @see elgg_get_entities_from_annotations()
	 */
	function getEntitiesFromCalculation($options) {
		
		if (isset($options['count']) && $options['count']) {
			return elgg_get_entities_from_annotations($options);
		}
		
		$db_prefix = _elgg_services()->config->get('dbprefix');
		$defaults = array(
			'calculation' => 'sum',
			'order_by' => 'annotation_calculation desc'
		);
	
		$options = array_merge($defaults, $options);
	
		$function = sanitize_string(elgg_extract('calculation', $options, 'sum', false));
	
		// you must cast this as an int or it sorts wrong.
		$options['selects'][] = 'e.*';
		$options['selects'][] = "$function(CAST(a_msv.string AS signed)) AS annotation_calculation";
	
		// need our own join to get the values because the lower level functions don't
		// add all the joins if it's a different callback.
		$options['joins'][] = "JOIN {$db_prefix}metastrings a_msv ON n_table.value_id = a_msv.id";
	
		// don't need access control because it's taken care of by elgg_get_annotations.
		$options['group_by'] = 'n_table.entity_guid';

		// do not default to a callback function used in elgg_get_annotation()
		if (!isset($options['callback'])) {
			$options['callback'] = 'entity_row_to_elggstar';
		}

		return elgg_get_annotations($options);
	}
	
	/**
	 * Check to see if a user has already created an annotation on an object
	 *
	 * @param int    $entity_guid     Entity guid
	 * @param string $annotation_type Type of annotation
	 * @param int    $owner_guid      Defaults to logged in user.
	 *
	 * @return bool
	 */
	function exists($entity_guid, $annotation_type, $owner_guid = null) {
		
	
		if (!$owner_guid && !($owner_guid = _elgg_services()->session->getLoggedInUserGuid())) {
			return false;
		}
	
		$entity_guid = sanitize_int($entity_guid);
		$owner_guid = sanitize_int($owner_guid);
		$annotation_type = sanitize_string($annotation_type);
	
		$sql = "SELECT a.id FROM {$this->CONFIG->dbprefix}annotations a" .
				" JOIN {$this->CONFIG->dbprefix}metastrings m ON a.name_id = m.id" .
				" WHERE a.owner_guid = $owner_guid AND a.entity_guid = $entity_guid" .
				" AND m.string = '$annotation_type'";
	
		if (_elgg_services()->db->getDataRow($sql)) {
			return true;
		}
	
		return false;
	}
}