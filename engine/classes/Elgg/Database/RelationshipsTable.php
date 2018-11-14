<?php
namespace Elgg\Database;

use Elgg\Database;
use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\EventsService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
	 * @return \ElggRelationship|false False if not found
	 */
	public function get($id) {
		$row = $this->getRow($id);
		if (!$row) {
			return false;
		}

		return new \ElggRelationship($row);
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
		$sql = "SELECT * FROM {$this->db->prefix}entity_relationships WHERE id = :id";
		$params = [
			':id' => (int) $id,
		];
		return $this->db->getDataRow($sql, null, $params);
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
		$id = (int) $id;

		$relationship = $this->get($id);

		if ($call_event && !$this->events->trigger('delete', 'relationship', $relationship)) {
			return false;
		}

		$sql = "DELETE FROM {$this->db->prefix}entity_relationships WHERE id = :id";
		$params = [
			':id' => $id,
		];
		return (bool) $this->db->deleteData($sql, $params);
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
	 * @throws \InvalidArgumentException
	 */
	public function add($guid_one, $relationship, $guid_two, $return_id = false) {
		if (strlen($relationship) > \ElggRelationship::RELATIONSHIP_LIMIT) {
			$msg = "relationship name cannot be longer than " . \ElggRelationship::RELATIONSHIP_LIMIT;
			throw new \InvalidArgumentException($msg);
		}

		// Check for duplicates
		// note: escape $relationship after this call, we don't want to double-escape
		if ($this->check($guid_one, $relationship, $guid_two)) {
			return false;
		}
		
		$sql = "
			INSERT INTO {$this->db->prefix}entity_relationships
			       (guid_one, relationship, guid_two, time_created)
			VALUES (:guid1, :relationship, :guid2, :time)
		";
		$params = [
			':guid1' => (int) $guid_one,
			':guid2' => (int) $guid_two,
			':relationship' => $relationship,
			':time' => $this->getCurrentTime()->getTimestamp(),
		];

		try {
			$id = $this->db->insertData($sql, $params);
			if (!$id) {
				return false;
			}
		} catch (\DatabaseException $e) {
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
		$query = "
			SELECT * FROM {$this->db->prefix}entity_relationships
			WHERE guid_one = :guid1
			  AND relationship = :relationship
			  AND guid_two = :guid2
			LIMIT 1
		";
		$params = [
			':guid1' => (int) $guid_one,
			':guid2' => (int) $guid_two,
			':relationship' => $relationship,
		];
		$row = $this->rowToElggRelationship($this->db->getDataRow($query, null, $params));
		if ($row) {
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
		if (!$obj) {
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
	 * @return \ElggRelationship[]
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
	 * @return \ElggRelationship|false
	 * @access private
	 */
	public function rowToElggRelationship($row) {
		if ($row instanceof \stdClass) {
			return new \ElggRelationship($row);
		}

		return false;
	}
}
