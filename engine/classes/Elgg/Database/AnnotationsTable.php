<?php
namespace Elgg\Database;
use Elgg\Database\Clauses\AnnotationWhereClause;
use ElggAnnotation;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class AnnotationsTable {

	use \Elgg\TimeUsing;
	
	/**
	 * @var \Elgg\Database
	 */
	protected $db;

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * @var \Elgg\EventsService
	 */
	protected $events;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Database      $db      Database
	 * @param \ElggSession        $session Session
	 * @param \Elgg\EventsService $events  Events
	 */
	public function __construct(\Elgg\Database $db, \ElggSession $session, \Elgg\EventsService $events) {
		$this->db = $db;
		$this->session = $session;
		$this->events = $events;
	}

	/**
	 * Get a specific annotation by its id.
	 * If you want multiple annotation objects, use
	 * {@link elgg_get_annotations()}.
	 *
	 * @param int $id The id of the annotation object being retrieved.
	 *
	 * @return ElggAnnotation|false
	 */
	function get($id) {
		$qb = Select::fromTable('annotations');
		$qb->select('*');

		$where = new AnnotationWhereClause();
		$where->ids = $id;
		$qb->addClause($where);

		$row = $this->db->getDataRow($qb);
		if ($row) {
			return new ElggAnnotation($row);
		}

		return false;
	}
	
	/**
	 * Deletes an annotation using its ID.
	 *
	 * @param int $id The annotation ID to delete.
	 * @return bool
	 */
	function delete($id) {
		$annotation = $this->get($id);
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
	
		$entity_guid = (int) $entity_guid;
		$value_type = \ElggExtender::detectValueType($value, $value_type);

		$owner_guid = (int) $owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}
	
		$access_id = (int) $access_id;

		$entity = get_entity($entity_guid);

		if (!$entity) {
			_elgg_services()->logger->error("Unable to load en entity with $entity_guid to annotate it");
			$entity_type = null;
		} else {
			$entity_type = $entity->type;
		}

		if ($this->events->trigger('annotate', $entity_type, $entity)) {
			$sql = "INSERT INTO {$this->db->prefix}annotations
				(entity_guid, name, value, value_type, owner_guid, time_created, access_id)
				VALUES
				(:entity_guid, :name, :value, :value_type, :owner_guid, :time_created, :access_id)";
	
			$result = $this->db->insertData($sql, [
				':entity_guid' => $entity_guid,
				':name' => $name,
				':value' => $value,
				':value_type' => $value_type,
				':owner_guid' => $owner_guid,
				':time_created' => $this->getCurrentTime()->getTimestamp(),
				':access_id' => $access_id,
			]);
				
			if ($result !== false) {
				$obj = elgg_get_annotation_from_id($result);
				if ($this->events->trigger('create', 'annotation', $obj)) {
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

		$annotation_id = (int) $annotation_id;
	
		$annotation = $this->get($annotation_id);
		if (!$annotation) {
			return false;
		}
		if (!$annotation->canEdit()) {
			return false;
		}
	
		$name = trim($name);
		$value_type = \ElggExtender::detectValueType($value, $value_type);
	
		$owner_guid = (int) $owner_guid;
		if ($owner_guid == 0) {
			$owner_guid = $this->session->getLoggedInUserGuid();
		}
	
		$access_id = (int) $access_id;
				
		$sql = "UPDATE {$this->db->prefix}annotations
			(name, value, value_type, access_id, owner_guid)
			VALUES
			(:name, :value, :value_type, :access_id, :owner_guid)
			WHERE id = :annotation_id";

		$result = $this->db->updateData($sql, false, [
			':name' => $name,
			':value' => $value,
			':value_type' => $value_type,
			':access_id' => $access_id,
			':owner_guid' => $owner_guid,
			':annotation_id' => $annotation_id,
		]);
			
		if ($result !== false) {
			// @todo add plugin hook that sends old and new annotation information before db access
			$obj = $this->get($annotation_id);
			$this->events->trigger('update', 'annotation', $obj);
		}
	
		return $result;
	}
	
	/**
	 * Returns annotations.  Accepts all elgg_get_entities() options for entity
	 * restraints.
	 *
	 * @see elgg_get_entities()
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
	 * @return ElggAnnotation[]|mixed
	 */
	function find(array $options = []) {
		$options['metastring_type'] = 'annotations';
		$options = _elgg_normalize_metastrings_options($options);
		return Annotations::find($options);
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

		$options['batch'] = true;
		$options['batch_size'] = 50;
		$options['batch_inc_offset'] = false;

		$annotations = Annotations::find($options);
		$count = $annotations->count();

		if (!$count) {
			return;
		}

		$success = 0;
		foreach ($annotations as $annotation) {
			if ($annotation->delete()) {
				$success++;
			}
		}

		return $success == $count;
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

		$options['batch'] = true;
		$options['batch_size'] = 50;
		$options['batch_inc_offset'] = $inc_offset;

		$annotations = Annotations::find($options);
		$count = $annotations->count();

		if (!$count) {
			return;
		}

		$success = 0;
		foreach ($annotations as $annotation) {
			if ($annotation->disable()) {
				$success++;
			}
		}

		return $success == $count;
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
		if (!_elgg_is_valid_options_for_batch_operation($options, 'annotation')) {
			return false;
		}

		$options['batch'] = true;
		$options['batch_size'] = 50;

		$annotations = Annotations::find($options);
		$count = $annotations->count();

		if (!$count) {
			return;
		}

		$success = 0;
		foreach ($annotations as $annotation) {
			if ($annotation->enable()) {
				$success++;
			}
		}

		return $success == $count;
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
	
		if (!$owner_guid && !($owner_guid = $this->session->getLoggedInUserGuid())) {
			return false;
		}

		$sql = "SELECT id FROM {$this->db->prefix}annotations
				WHERE owner_guid = :owner_guid
				AND entity_guid = :entity_guid
				AND name = :annotation_type";

		$result = $this->db->getDataRow($sql, null, [
			':owner_guid' => (int) $owner_guid,
			':entity_guid' => (int) $entity_guid,
			':annotation_type' => $annotation_type,
		]);
	
		return (bool) $result;
	}
}
