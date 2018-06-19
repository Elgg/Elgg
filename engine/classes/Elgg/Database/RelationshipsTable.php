<?php
namespace Elgg\Database;

use DatabaseException;
use Elgg\Database;
use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\EventsService;
use ElggRelationship;
use InvalidArgumentException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class RelationshipsTable {

	use \Elgg\TimeUsing;
	
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
	 * @return ElggRelationship|false False if not found
	 */
	public function get($id) {
		$row = $this->getRow($id);
		if (!$row) {
			return false;
		}

		return new ElggRelationship($row);
	}

	/**
	 * Get a database row from the relationship table
	 *
	 * @param int $id The relationship ID
	 *
	 * @return \stdClass|false False if no row found
	 * @access private
	 */
	public function getRow($id) {
		$qb = Select::fromTable('entity_relationships');
		$qb->select('*')
			->where($qb->compare('id', '=', $id, ELGG_VALUE_INTEGER));

		return $this->db->getDataRow($qb);
	}

	/**
	 * Delete a relationship by its ID
	 *
	 * @param ElggRelationship $relationship Relationship
	 *
	 * @return bool
	 */
	public function delete(ElggRelationship $relationship) {

		if (!$relationship->id) {
			return false;
		}

		if (!$this->events->triggerBefore('delete', 'relationship', $relationship)) {
			return false;
		}

		if (!$this->events->triggerDeprecated('delete', 'relationship', $relationship)) {
			return false;
		}

		$qb = Delete::fromTable('entity_relationships');
		$qb->where($qb->compare('id', '=', $relationship->id, ELGG_VALUE_INTEGER));

		$result = (bool) $this->db->deleteData($qb);

		if ($result) {
			$this->events->triggerAfter('delete', 'relationship', $relationship);
			return true;
		}

		return false;
	}

	/**
	 * Create a relationship between two entities. E.g. friendship, group membership, site membership.
	 *
	 * This function lets you make the statement "$guid_one is a $relationship of $guid_two". In the statement,
	 * $guid_one is the subject of the relationship, $guid_two is the target, and $relationship is the type.
	 *
	 * @param ElggRelationship $relationship Relationship
	 *
	 * @return bool|int
	 * @throws DatabaseException
	 */
	public function add(ElggRelationship $relationship) {
		if (strlen($relationship->relationship) > ElggRelationship::RELATIONSHIP_LIMIT) {
			$msg = "relationship name cannot be longer than " . ElggRelationship::RELATIONSHIP_LIMIT;
			throw new InvalidArgumentException($msg);
		}

		$guid_one = $relationship->guid_one;
		$name = $relationship->relationship;
		$guid_two = $relationship->guid_two;

		// Check for duplicates
		// note: escape $name after this call, we don't want to double-escape
		if ($relationship->id || $this->check($guid_one, $name, $guid_two)) {
			return false;
		}

		if (!$this->events->triggerBefore('create', 'relationship', $relationship)) {
			return false;
		}

		$created = $relationship->time_created ? : $this->getCurrentTime()->getTimestamp();

		try {
			$qb = Insert::intoTable('entity_relationships');
			$qb->values([
				'guid_one' => $qb->param($guid_one, ELGG_VALUE_INTEGER),
				'relationship' => $qb->param($name, ELGG_VALUE_STRING),
				'guid_two' => $qb->param($guid_two, ELGG_VALUE_INTEGER),
				'time_created' => $qb->param($created, ELGG_VALUE_INTEGER),
			]);

			$id = $this->db->insertData($qb);
			if (!$id) {
				throw new DatabaseException();
			}
		} catch (DatabaseException $ex) {
			return false;
		}

		$relationship->id = $id;
		$relationship->time_created = $created;

		if (!$this->events->triggerDeprecated('create', 'relationship', $relationship)) {
			$relationship->delete();
			return false;
		}

		$this->events->triggerAfter('create', 'relationship', $relationship);

		return $id;
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
	 * @return ElggRelationship|false Depending on success
	 */
	public function check($guid_one, $relationship, $guid_two) {
		$qb = Select::fromTable('entity_relationships');
		$qb->select('*')
			->where($qb->merge([
				$qb->compare('guid_one', '=', $guid_one, ELGG_VALUE_INTEGER),
				$qb->compare('relationship', '=', $relationship, ELGG_VALUE_STRING),
				$qb->compare('guid_two', '=', $guid_two, ELGG_VALUE_INTEGER),
			]))
			->setMaxResults(1);

		$callback = [$this, 'rowToElggRelationship'];

		$result = $this->db->getDataRow($qb, $callback);

		if (empty($result)) {
			return false;
		}

		return $result;
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
		$rel = $this->check($guid_one, $relationship, $guid_two);
		if (!$rel) {
			return false;
		}

		return $rel->delete();
	}

	/**
	 * Removes all relationships originating from a particular entity
	 *
	 * @param int    $guid                 GUID of the subject or target entity (see $inverse)
	 * @param string $relationship         Type of the relationship (optional, default is all relationships)
	 * @param bool   $inverse_relationship Is $guid the target of the deleted relationships? By default, $guid is the
	 *                                     subject of the relationships.
	 * @param string $type                 The type of entity related to $guid (defaults to all)
	 *
	 * @return true
	 */
	public function removeAll($guid, $relationship = "", $inverse_relationship = false, $type = '') {
		$guid = (int) $guid;
		$params = [];

		if (!empty($relationship)) {
			$where = "AND er.relationship = :relationship";
			$params[':relationship'] = $relationship;
		} else {
			$where = "";
		}

		if (!empty($type)) {
			if (!$inverse_relationship) {
				$join = "JOIN {$this->db->prefix}entities e ON e.guid = er.guid_two";
			} else {
				$join = "JOIN {$this->db->prefix}entities e ON e.guid = er.guid_one";
				$where .= " AND ";
			}
			$where .= " AND e.type = :type";
			$params[':type'] = $type;
		} else {
			$join = "";
		}

		$guid_col = $inverse_relationship ? "guid_two" : "guid_one";

		$this->db->deleteData("
			DELETE er FROM {$this->db->prefix}entity_relationships AS er
			$join
			WHERE $guid_col = $guid
			$where
		", $params);

		return true;
	}

	/**
	 * Get all the relationships for a given GUID.
	 *
	 * @param int  $guid                 GUID of the subject or target entity (see $inverse)
	 * @param bool $inverse_relationship Is $guid the target of the deleted relationships? By default $guid is
	 *                                   the subject of the relationships.
	 *
	 * @return ElggRelationship[]
	 */
	public function getAll($guid, $inverse_relationship = false) {
		$params[':guid'] = (int) $guid;

		$where = ($inverse_relationship ? "guid_two = :guid" : "guid_one = :guid");

		$query = "SELECT * from {$this->db->prefix}entity_relationships WHERE {$where}";

		return $this->db->getData($query, [$this, 'rowToElggRelationship'], $params);
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
	 * @return ElggRelationship|false
	 * @access private
	 */
	public function rowToElggRelationship($row) {
		if ($row instanceof \stdClass) {
			return new ElggRelationship($row);
		}

		return false;
	}
}
