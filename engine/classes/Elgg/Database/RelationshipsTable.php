<?php

namespace Elgg\Database;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Elgg\Database;
use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\EventsService;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Traits\TimeUsing;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 *
 * @since 1.10.0
 */
class RelationshipsTable {

	use TimeUsing;
	
	/**
	 * @var integer The max length of the relationship column data
	 */
	const RELATIONSHIP_COLUMN_LENGTH = 255;
	
	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var EntityTable
	 */
	protected $entities;

	/**
	 * @var MetadataTable
	 */
	protected $metadata;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * Constructor
	 *
	 * @param Database      $db       Elgg Database
	 * @param EntityTable   $entities Entity table
	 * @param MetadataTable $metadata Metadata table
	 * @param EventsService $events   Events service
	 */
	public function __construct(Database $db, EntityTable $entities, MetadataTable $metadata, EventsService $events) {
		$this->db = $db;
		$this->entities = $entities;
		$this->metadata = $metadata;
		$this->events = $events;
	}

	/**
	 * Get a relationship by its ID
	 *
	 * @param int $id The relationship ID
	 *
	 * @return \ElggRelationship|false False if not found
	 */
	public function get($id) {
		$select = Select::fromTable('entity_relationships');
		$select->select('*')
			->where($select->compare('id', '=', $id, ELGG_VALUE_ID));
		
		$relationship = $this->db->getDataRow($select, [$this, 'rowToElggRelationship']);
		if (!$relationship) {
			return false;
		}

		return $relationship;
	}

	/**
	 * Delete a relationship by its ID
	 *
	 * @param int  $id         Relationship ID
	 * @param bool $call_event Call the delete event before deleting
	 *
	 * @return bool
	 */
	public function delete($id, $call_event = true) {
		$relationship = $this->get($id);
		if (!$relationship instanceof \ElggRelationship) {
			return false;
		}

		if ($call_event && !$this->events->trigger('delete', 'relationship', $relationship)) {
			return false;
		}

		$delete = Delete::fromTable('entity_relationships');
		$delete->where($delete->compare('id', '=', $id, ELGG_VALUE_ID));
		
		return (bool) $this->db->deleteData($delete);
	}

	/**
	 * Create a relationship between two entities. E.g. friendship, group membership, site membership.
	 *
	 * This function lets you make the statement "$guid_one is a $relationship of $guid_two". In the statement,
	 * $guid_one is the subject of the relationship, $guid_two is the target, and $relationship is the type.
	 *
	 * @param int    $guid_one     GUID of the subject entity of the relationship
	 * @param string $relationship Type of the relationship
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param bool   $return_id    Return the ID instead of bool?
	 *
	 * @return bool|int
	 * @throws InvalidArgumentException
	 */
	public function add($guid_one, $relationship, $guid_two, $return_id = false) {
		if (strlen($relationship) > self::RELATIONSHIP_COLUMN_LENGTH) {
			throw new InvalidArgumentException('Relationship name cannot be longer than ' . self::RELATIONSHIP_COLUMN_LENGTH);
		}

		// Check for duplicates
		// note: escape $relationship after this call, we don't want to double-escape
		if ($this->check($guid_one, $relationship, $guid_two)) {
			return false;
		}
		
		// Check if the related entities exist
		if (!$this->entities->exists($guid_one) || !$this->entities->exists($guid_two)) {
			// one or both of the guids doesn't exist
			return false;
		}
		
		$insert = Insert::intoTable('entity_relationships');
		$insert->values([
			'guid_one' => $insert->param($guid_one, ELGG_VALUE_GUID),
			'relationship' => $insert->param($relationship, ELGG_VALUE_STRING),
			'guid_two' => $insert->param($guid_two, ELGG_VALUE_GUID),
			'time_created' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		try {
			$id = $this->db->insertData($insert);
			if (!$id) {
				return false;
			}
		} catch (DatabaseException $e) {
			$prev = $e->getPrevious();
			if ($prev instanceof UniqueConstraintViolationException) {
				// duplicate key error see https://github.com/Elgg/Elgg/issues/9179
				return false;
			}
			throw $e;
		}

		$obj = $this->get($id);

		$result = $this->events->trigger('create', 'relationship', $obj);
		if (!$result) {
			$this->delete($id, false);
			return false;
		}

		return $return_id ? $obj->id : true;
	}

	/**
	 * Check if a relationship exists between two entities. If so, the relationship object is returned.
	 *
	 * This function lets you ask "Is $guid_one a $relationship of $guid_two?"
	 *
	 * @param int    $guid_one     GUID of the subject entity of the relationship
	 * @param string $relationship Type of the relationship
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 *
	 * @return \ElggRelationship|false Depending on success
	 */
	public function check($guid_one, $relationship, $guid_two) {
		$select = Select::fromTable('entity_relationships');
		$select->select('*')
			->where($select->compare('guid_one', '=', $guid_one, ELGG_VALUE_GUID))
			->andWhere($select->compare('relationship', '=', $relationship, ELGG_VALUE_STRING))
			->andWhere($select->compare('guid_two', '=', $guid_two, ELGG_VALUE_GUID))
			->setMaxResults(1);
		
		$row = $this->db->getDataRow($select, [$this, 'rowToElggRelationship']);
		if ($row instanceof \ElggRelationship) {
			return $row;
		}

		return false;
	}

	/**
	 * Delete a relationship between two entities.
	 *
	 * This function lets you say "$guid_one is no longer a $relationship of $guid_two."
	 *
	 * @param int    $guid_one     GUID of the subject entity of the relationship
	 * @param string $relationship Type of the relationship
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 *
	 * @return bool
	 */
	public function remove($guid_one, $relationship, $guid_two) {
		$obj = $this->check($guid_one, $relationship, $guid_two);
		if (!$obj instanceof \ElggRelationship) {
			return false;
		}

		return $this->delete($obj->id);
	}

	/**
	 * Removes all relationships originating from a particular entity
	 *
	 * @param int    $guid                 GUID of the subject or target entity (see $inverse)
	 * @param string $relationship         Type of the relationship (optional, default is all relationships)
	 * @param bool   $inverse_relationship Is $guid the target of the deleted relationships? By default, $guid is the
	 *                                     subject of the relationships.
	 * @param string $type                 The type of entity related to $guid (defaults to all)
	 * @param bool   $trigger_events       Trigger the delete event for each relationship (default: true)
	 *
	 * @return true
	 */
	public function removeAll($guid, $relationship = '', $inverse_relationship = false, $type = '', bool $trigger_events = true) {
		
		if ($trigger_events) {
			return $this->removeAllWithEvents($guid, $relationship, $inverse_relationship, $type);
		}
		
		return $this->removeAllWithoutEvents($guid, $relationship, $inverse_relationship, $type);
	}
	
	/**
	 * Removes all relationships originating from a particular entity
	 *
	 * This doesn't trigger the delete event for each relationship
	 *
	 * @param int    $guid                 GUID of the subject or target entity (see $inverse)
	 * @param string $relationship         Type of the relationship (optional, default is all relationships)
	 * @param bool   $inverse_relationship Is $guid the target of the deleted relationships? By default, $guid is the
	 *                                     subject of the relationships.
	 * @param string $type                 The type of entity related to $guid (defaults to all)
	 *
	 * @return true
	 */
	protected function removeAllWithoutEvents($guid, $relationship = '', $inverse_relationship = false, $type = '') {
		$delete = Delete::fromTable('entity_relationships');
		
		if ((bool) $inverse_relationship) {
			$delete->where($delete->compare('guid_two', '=', $guid, ELGG_VALUE_GUID));
		} else {
			$delete->where($delete->compare('guid_one', '=', $guid, ELGG_VALUE_GUID));
		}
		
		if (!empty($relationship)) {
			$delete->andWhere($delete->compare('relationship', '=', $relationship, ELGG_VALUE_STRING));
		}
		
		if (!empty($type)) {
			$entity_sub = $delete->subquery('entities');
			$entity_sub->select('guid')
			->where($delete->compare('type', '=', $type, ELGG_VALUE_STRING));
			
			if (!(bool) $inverse_relationship) {
				$delete->andWhere($delete->compare('guid_two', 'in', $entity_sub->getSQL()));
			} else {
				$delete->andWhere($delete->compare('guid_one', 'in', $entity_sub->getSQL()));
			}
		}
		
		$this->db->deleteData($delete);
		
		return true;
	}
	
	/**
	 * Removes all relationships originating from a particular entity
	 *
	 * The does trigger the delete event for each relationship
	 *
	 * @param int    $guid                 GUID of the subject or target entity (see $inverse)
	 * @param string $relationship         Type of the relationship (optional, default is all relationships)
	 * @param bool   $inverse_relationship Is $guid the target of the deleted relationships? By default, $guid is the
	 *                                     subject of the relationships.
	 * @param string $type                 The type of entity related to $guid (defaults to all)
	 *
	 * @return true
	 */
	protected function removeAllWithEvents($guid, $relationship = '', $inverse_relationship = false, $type = '') {
		$select = Select::fromTable('entity_relationships');
		$select->select('*');
		
		if ((bool) $inverse_relationship) {
			$select->where($select->compare('guid_two', '=', $guid, ELGG_VALUE_GUID));
		} else {
			$select->where($select->compare('guid_one', '=', $guid, ELGG_VALUE_GUID));
		}
		
		if (!empty($relationship)) {
			$select->andWhere($select->compare('relationship', '=', $relationship, ELGG_VALUE_STRING));
		}
		
		if (!empty($type)) {
			$entity_sub = $select->subquery('entities');
			$entity_sub->select('guid')
			->where($select->compare('type', '=', $type, ELGG_VALUE_STRING));
			
			if (!(bool) $inverse_relationship) {
				$select->andWhere($select->compare('guid_two', 'in', $entity_sub->getSQL()));
			} else {
				$select->andWhere($select->compare('guid_one', 'in', $entity_sub->getSQL()));
			}
		}
		
		$remove_ids = [];
		
		$relationships = $this->db->getData($select, [$this, 'rowToElggRelationship']);
		
		/* @var $rel \ElggRelationship */
		foreach ($relationships as $rel) {
			if (!$this->events->trigger('delete', 'relationship', $rel)) {
				continue;
			}
			
			$remove_ids[] = $rel->id;
		}
		
		// to prevent MySQL query length issues
		$chunks = array_chunk($remove_ids, 250);
		foreach ($chunks as $chunk) {
			if (empty($chunk)) {
				continue;
			}
			
			$delete = Delete::fromTable('entity_relationships');
			$delete->where($delete->compare('id', 'in', $chunk));
			
			$this->db->deleteData($delete);
		}
		
		return true;
	}

	/**
	 * Get all the relationships for a given GUID.
	 *
	 * @param int  $guid                 GUID of the subject or target entity (see $inverse)
	 * @param bool $inverse_relationship Is $guid the target of the deleted relationships? By default $guid is
	 *                                   the subject of the relationships.
	 *
	 * @return \ElggRelationship[]
	 */
	public function getAll($guid, $inverse_relationship = false) {
		$select = Select::fromTable('entity_relationships');
		$select->select('*');
		
		if ((bool) $inverse_relationship) {
			$select->where($select->compare('guid_two', '=', $guid, ELGG_VALUE_GUID));
		} else {
			$select->where($select->compare('guid_one', '=', $guid, ELGG_VALUE_GUID));
		}
		
		return $this->db->getData($select, [$this, 'rowToElggRelationship']);
	}

	/**
	 * Gets the number of entities by a the number of entities related to them in a particular way.
	 * This is a good way to get out the users with the most friends, or the groups with the
	 * most members.
	 *
	 * @param array $options An options array compatible with elgg_get_entities()
	 *
	 * @return \ElggEntity[]|int|boolean If count, int. If not count, array. false on errors.
	 */
	public function getEntitiesFromCount(array $options = []) {
		$options['selects'][] = new SelectClause("COUNT(e.guid) AS total");
		$options['group_by'][] = new GroupByClause('r.guid_two');
		$options['order_by'][] = new OrderByClause('total', 'desc');

		return Entities::find($options);
	}

	/**
	 * Convert a database row to a new \ElggRelationship
	 *
	 * @param \stdClass $row Database row from the relationship table
	 *
	 * @return \ElggRelationship|false
	 */
	public function rowToElggRelationship($row) {
		if ($row instanceof \stdClass) {
			return new \ElggRelationship($row);
		}

		return false;
	}
}
