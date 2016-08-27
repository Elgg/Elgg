<?php
namespace Elgg\Database;

use Elgg\Database;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class SubtypeTable {

	/**
	 * @var \stdClass[]|null
	 */
	protected $cache = null;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param Database $db Elgg database
	 */
	public function __construct(Database $db) {
		$this->db = $db;
	}

	/**
	 * Set the cached values from the boot data
	 *
	 * @param array $values Values from boot data
	 * @return void
	 */
	public function setCachedValues(array $values) {
		$this->cache = $values;
	}

	/**
	 * Return the id for a given subtype.
	 *
	 * \ElggEntity objects have a type and a subtype.  Subtypes
	 * are defined upon creation and cannot be changed.
	 *
	 * Plugin authors generally don't need to use this function
	 * unless writing their own SQL queries.  Use {@link \ElggEntity::getSubtype()}
	 * to return the string subtype.
	 *
	 * @internal Subtypes are stored in the entity_subtypes table.  There is a foreign
	 * key in the entities table.
	 *
	 * @param string $type    Type
	 * @param string $subtype Subtype
	 *
	 * @return int Subtype ID
	 * @see get_subtype_from_id()
	 * @access private
	 */
	public function getId($type, $subtype) {
		if (!$subtype) {
			return false;
		}

		$obj = $this->retrieveFromCache($type, $subtype);

		return $obj ? $obj->id : false;
	}
	
	/**
	 * Gets the denormalized string for a given subtype ID.
	 *
	 * @param int $subtype_id Subtype ID from database
	 * @return string|false Subtype name, false if subtype not found
	 * @see get_subtype_id()
	 * @access private
	 */
	public function getSubtype($subtype_id) {
		if (!$subtype_id) {
			return '';
		}

		$cache = $this->getPopulatedCache();

		return isset($cache[$subtype_id]) ? $cache[$subtype_id]->subtype : false;
	}
	
	/**
	 * Retrieve subtype from the cache.
	 *
	 * @param string $type
	 * @param string $subtype
	 * @return \stdClass|null
	 *
	 * @access private
	 */
	public function retrieveFromCache($type, $subtype) {
		foreach ($this->getPopulatedCache() as $obj) {
			if ($obj->type === $type && $obj->subtype === $subtype) {
				return $obj;
			}
		}

		return null;
	}

	/**
	 * Return the class name for a registered type and subtype.
	 *
	 * Entities can be registered to always be loaded as a certain class
	 * with add_subtype() or update_subtype(). This function returns the class
	 * name if found and null if not.
	 *
	 * @param string $type    The type
	 * @param string $subtype The subtype
	 *
	 * @return string|null a class name or null
	 * @see get_subtype_from_id()
	 * @see get_subtype_class_from_id()
	 * @access private
	 */
	public function getClass($type, $subtype) {
		$obj = $this->retrieveFromCache($type, $subtype);

		return $obj ? $obj->class : null;
	}

	/**
	 * Returns the class name for a subtype id.
	 *
	 * @param int $subtype_id The subtype id
	 *
	 * @return string|null
	 * @see get_subtype_class()
	 * @see get_subtype_from_id()
	 * @access private
	 */
	public function getClassFromId($subtype_id) {
		if (!$subtype_id) {
			return null;
		}

		$cache = $this->getPopulatedCache();

		return isset($cache[$subtype_id]) ? $cache[$subtype_id]->class : null;
	}
	
	/**
	 * Register \ElggEntities with a certain type and subtype to be loaded as a specific class.
	 *
	 * By default entities are loaded as one of the 4 parent objects: site, user, object, or group.
	 * If you subclass any of these you can register the classname with add_subtype() so
	 * it will be loaded as that class automatically when retrieved from the database with
	 * {@link get_entity()}.
	 *
	 * @warning This function cannot be used to change the class for a type-subtype pair.
	 * Use update_subtype() for that.
	 *
	 * @param string $type    The type you're subtyping (site, user, object, or group)
	 * @param string $subtype The subtype
	 * @param string $class   Optional class name for the object
	 *
	 * @return int
	 * @see update_subtype()
	 * @see remove_subtype()
	 * @see get_entity()
	 */
	public function add($type, $subtype, $class = "") {
		if (!$subtype) {
			return 0;
		}
	
		$id = $this->getId($type, $subtype);
	
		if (!$id) {
			$sql = "
				INSERT INTO {$this->db->prefix}entity_subtypes
					(type,  subtype,  class) VALUES
					(:type, :subtype, :class)
			";
			$params = [
				':type' => $type,
				':subtype' => $subtype,
				':class' => $class,
			];
			$id = $this->db->insertData($sql, $params);

			$this->invalidateCache();
		}
	
		return $id;
	}
	
	/**
	 * Removes a registered \ElggEntity type, subtype, and classname.
	 *
	 * @warning You do not want to use this function. If you want to unregister
	 * a class for a subtype, use update_subtype(). Using this function will
	 * permanently orphan all the objects created with the specified subtype.
	 *
	 * @param string $type    Type
	 * @param string $subtype Subtype
	 *
	 * @return bool
	 * @see add_subtype()
	 * @see update_subtype()
	 */
	public function remove($type, $subtype) {
		$sql = "
			DELETE FROM {$this->db->prefix}entity_subtypes
			WHERE type = :type AND subtype = :subtype
		";
		$params = [
			':type' => $type,
			':subtype' => $subtype,
		];
		if (!$this->db->deleteData($sql, $params)) {
			return false;
		}

		$this->invalidateCache();
		
		return true;
	}
	
	/**
	 * Update a registered \ElggEntity type, subtype, and class name
	 *
	 * @param string $type    Type
	 * @param string $subtype Subtype
	 * @param string $class   Class name to use when loading this entity
	 *
	 * @return bool
	 */
	public function update($type, $subtype, $class = '') {
		$id = $this->getId($type, $subtype);
		if (!$id) {
			return false;
		}
	
		$sql = "
			UPDATE {$this->db->prefix}entity_subtypes
			SET type = :type, subtype = :subtype, class = :class
			WHERE id = :id
		";
		$params = [
			':type' => $type,
			':subtype' => $subtype,
			':class' => $class,
			':id' => $id,
		];
		if (!$this->db->updateData($sql, false, $params)) {
			return false;
		}

		$this->invalidateCache();

		return true;
	}

	/**
	 * Empty the cache. Also invalidates the boot cache and memcache
	 *
	 * @return void
	 */
	protected function invalidateCache() {
		$this->cache = null;
		_elgg_services()->boot->invalidateCache();
		_elgg_services()->entityCache->clear();
	}

	/**
	 * Get a populated cache object
	 *
	 * @return array
	 */
	protected function getPopulatedCache() {
		if ($this->cache === null) {
			$rows = $this->db->getData("
				SELECT *
				FROM {$this->db->prefix}entity_subtypes
			");

			$this->cache = [];
			foreach ($rows as $row) {
				$this->cache[$row->id] = $row;
			}
		}

		return $this->cache;
	}
}
