<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\EventsService;
use ElggAnnotation;
use ElggEntity;

/**
 * Interfaces with the database to perform CRUD operations on annotations
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 */
class AnnotationsTable {

	use \Elgg\TimeUsing;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * Constructor
	 *
	 * @param Database      $db     Database
	 * @param EventsService $events Events
	 */
	public function __construct(Database $db, EventsService $events) {
		$this->db = $db;
		$this->events = $events;
	}

	/**
	 * Get a specific annotation by its id
	 *
	 * @param int $id The id of the annotation object
	 *
	 * @return ElggAnnotation|false
	 */
	public function get($id) {
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
	 * Deletes an annotation using its ID
	 *
	 * @param ElggAnnotation $annotation Annotation
	 *
	 * @return bool
	 */
	public function delete(ElggAnnotation $annotation) {
		if (!$annotation->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('delete', 'annotation', $annotation)) {
			return false;
		}

		$qb = Delete::fromTable('annotations');
		$qb->where($qb->compare('id', '=', $annotation->id, ELGG_VALUE_INTEGER));
		$deleted = $this->db->deleteData($qb);

		if ($deleted) {
			elgg_delete_river([
				'annotation_id' => $annotation->id,
				'limit' => false,
			]);
		}

		return $deleted !== false;
	}

	/**
	 * Create a new annotation and return its ID
	 *
	 * @param ElggAnnotation $annotation Annotation
	 * @param ElggEntity     $entity     Entity being annotated
	 *
	 * @return int|bool
	 */
	public function create(ElggAnnotation $annotation, ElggEntity $entity) {
		if ($annotation->id) {
			return $this->update($annotation);
		}

		$annotation->entity_guid = $entity->guid;

		// @todo It looks like annotations permissions are not being checked anywhere...
		// Uncomment once tests have been updated
		// See #11418
		//if (!$entity->canAnnotate(0, $annotation->name)) {
		//	return false;
		//}

		if (!$this->events->trigger('annotate', $entity->getType(), $entity)) {
			return false;
		}

		if (!$this->events->triggerBefore('create', 'annotation', $annotation)) {
			return false;
		}

		$time_created = $this->getCurrentTime()->getTimestamp();

		$qb = Insert::intoTable('annotations');
		$qb->values([
			'entity_guid' => $qb->param($annotation->entity_guid, ELGG_VALUE_INTEGER),
			'name' => $qb->param($annotation->name, ELGG_VALUE_STRING),
			'value' => $qb->param($annotation->value, $annotation->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING),
			'value_type' => $qb->param($annotation->value_type, ELGG_VALUE_STRING),
			'owner_guid' => $qb->param($annotation->owner_guid, ELGG_VALUE_INTEGER),
			'time_created' => $qb->param($time_created, ELGG_VALUE_INTEGER),
			'access_id' => $qb->param($annotation->access_id, ELGG_VALUE_INTEGER),
		]);

		$result = $this->db->insertData($qb);
		if ($result === false) {
			return false;
		}

		$annotation->id = $result;
		$annotation->time_created = $time_created;

		if (!$this->events->trigger('create', 'annotation', $annotation)) {
			elgg_delete_annotation_by_id($result);

			return false;
		}

		$this->events->triggerAfter('create', 'annotation', $annotation);

		return $result;
	}

	/**
	 * Store updated annotation in the database
	 *
	 * @todo Add canAnnotate check if entity guid changes
	 *
	 * @param ElggAnnotation $annotation Annotation to store
	 *
	 * @return bool
	 */
	public function update(ElggAnnotation $annotation) {
		if (!$annotation->canEdit()) {
			return false;
		}

		if (!$this->events->triggerBefore('update', 'annotation', $annotation)) {
			return false;
		}

		$qb = Update::table('annotations');
		$qb->set('name', $qb->param($annotation->name, ELGG_VALUE_STRING))
			->set('value', $qb->param($annotation->value, $annotation->value_type === 'integer' ? ELGG_VALUE_INTEGER : ELGG_VALUE_STRING))
			->set('value_type', $qb->param($annotation->value_type, ELGG_VALUE_STRING))
			->set('access_id', $qb->param($annotation->access_id, ELGG_VALUE_INTEGER))
			->set('owner_guid', $qb->param($annotation->owner_guid, ELGG_VALUE_INTEGER))
			->where($qb->compare('id', '=', $annotation->id, ELGG_VALUE_INTEGER));

		$result = $this->db->updateData($qb);

		if ($result === false) {
			return false;
		}

		$this->events->trigger('update', 'annotation', $annotation);
		$this->events->triggerAfter('update', 'annotation', $annotation);

		return $result;
	}

	/**
	 * Disable the annotation.
	 *
	 * @param ElggAnnotation $annotation Annotation
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function disable(ElggAnnotation $annotation) {
		if ($annotation->enabled == 'no') {
			return true;
		}

		if (!$annotation->canEdit()) {
			return false;
		}

		if (!_elgg_services()->events->trigger('disable', $annotation->getType(), $annotation)) {
			return false;
		}

		if ($annotation->id) {
			$qb = Update::table('annotations');
			$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING))
				->where($qb->compare('id', '=', $annotation->id, ELGG_VALUE_INTEGER));

			if (!$this->db->updateData($qb)) {
				return false;
			}
		}

		$annotation->enabled = 'no';

		return true;
	}

	/**
	 * Enable the annotation
	 *
	 * @param ElggAnnotation $annotation Annotation
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function enable(ElggAnnotation $annotation) {
		if ($annotation->enabled == 'yes') {
			return true;
		}

		if (!$annotation->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('enable', $annotation->getType(), $annotation)) {
			return false;
		}

		if ($annotation->id) {
			$qb = Update::table('annotations');
			$qb->set('enabled', $qb->param('yes', ELGG_VALUE_STRING))
				->where($qb->compare('id', '=', $annotation->id, ELGG_VALUE_INTEGER));

			if (!$this->db->updateData($qb)) {
				return false;
			}
		}

		$annotation->enabled = 'yes';

		return true;
	}

	/**
	 * Returns annotations.  Accepts all {@link elgg_get_entities()} options
	 *
	 * @see elgg_get_entities()
	 *
	 * @param array $options Options
	 *
	 * @return ElggAnnotation[]|mixed
	 */
	public function find(array $options = []) {
		$options['metastring_type'] = 'annotations';
		$options = LegacyQueryOptionsAdapter::normalizeMetastringOptions($options);

		return Annotations::find($options);
	}

	/**
	 * Deletes annotations based on $options.
	 *
	 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
	 *          This requires at least one constraint: annotation_owner_guid(s),
	 *          annotation_name(s), annotation_value(s), or guid(s) must be set.
	 *
	 * @see     elgg_get_annotations()
	 * @see     elgg_get_entities()
	 *
	 * @param array $options Options
	 *
	 * @return bool|null true on success, false on failure, null if no annotations to delete.
	 */
	public function deleteAll(array $options) {
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
	public function disableAll(array $options) {
		if (!_elgg_is_valid_options_for_batch_operation($options, 'annotation')) {
			return false;
		}

		// if we can see hidden (disabled) we need to use the offset
		// otherwise we risk an infinite loop if there are more than 50
		$inc_offset = _elgg_services()->session->getDisabledEntityVisibility();

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
	public function enableAll(array $options) {
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
	 * @param int    $entity_guid Entity guid
	 * @param string $name        Annotation name
	 * @param int    $owner_guid  Owner guid
	 *
	 * @return bool
	 */
	public function exists($entity_guid, $name, $owner_guid) {
		if (!$owner_guid) {
			return false;
		}

		$qb = Select::fromTable('annotations');
		$qb->select('id');
		$qb->where($qb->compare('owner_guid', '=', $owner_guid, ELGG_VALUE_INTEGER))
			->andWhere($qb->compare('entity_guid', '=', $entity_guid, ELGG_VALUE_INTEGER))
			->andWhere($qb->compare('name', '=', $name, ELGG_VALUE_STRING));

		$result = $this->db->getDataRow($qb);

		return $result && $result->id;
	}
}
