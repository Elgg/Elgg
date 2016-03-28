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
	 * @var array|null
	 */
	private $cache = null;

	/**
	 * @var Database
	 */
	private $db;

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
	function getId($type, $subtype) {
		if (!$subtype) {
			return false;
		}
	
		if ($this->cache === null) {
			$this->populateCache();
		}
	
		// use the cache before hitting database
		$result = $this->retrieveFromCache($type, $subtype);
		if ($result !== null) {
			return $result->id;
		}
	
		return false;
	}
	
	/**
	 * Gets the denormalized string for a given subtype ID.
	 *
	 * @param int $subtype_id Subtype ID from database
	 * @return string|false Subtype name, false if subtype not found
	 * @see get_subtype_id()
	 * @access private
	 */
	function getSubtype($subtype_id) {
		if (!$subtype_id) {
			return '';
		}
	
		if ($this->cache === null) {
			$this->populateCache();
		}
	
		if (isset($this->cache[$subtype_id])) {
			return $this->cache[$subtype_id]->subtype;
		}
	
		return false;
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
	function retrieveFromCache($type, $subtype) {
		if ($this->cache === null) {
			$this->populateCache();
		}
	
		foreach ($this->cache as $obj) {
			if ($obj->type === $type && $obj->subtype === $subtype) {
				return $obj;
			}
		}
		return null;
	}
	
	/**
	 * Fetch all suptypes from DB to local cache.
	 *
	 * @access private
	 */
	function populateCache() {
		$results = $this->db->getData("
			SELECT *
			FROM {$this->db->getTablePrefix()}entity_subtypes
		");

		$this->cache = array();
		foreach ($results as $row) {
			$this->cache[$row->id] = $row;
		}
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
	function getClass($type, $subtype) {
		if ($this->cache === null) {
			$this->populateCache();
		}
		
		// use the cache before going to the database
		$obj = $this->retrieveFromCache($type, $subtype);
		if ($obj) {
			return $obj->class;
		}
	
		return null;
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
	function getClassFromId($subtype_id) {
		if (!$subtype_id) {
			return null;
		}
	
		if ($this->cache === null) {
			$this->populateCache();
		}
		
		if (isset($this->cache[$subtype_id])) {
			return $this->cache[$subtype_id]->class;
		}
	
		return null;
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
	function add($type, $subtype, $class = "") {
		if (!$subtype) {
			return 0;
		}
	
		$id = $this->getId($type, $subtype);
	
		if (!$id) {
			// In cache we store non-SQL-escaped strings because that's what's returned by query
			$cache_obj = (object) array(
				'type' => $type,
				'subtype' => $subtype,
				'class' => $class,
			);
	
			$type = $this->db->sanitizeString($type);
			$subtype = $this->db->sanitizeString($subtype);
			$class = $this->db->sanitizeString($class);
	
			$id = $this->db->insertData("
				INSERT INTO {$this->db->getTablePrefix()}entity_subtypes
					(type, subtype, class) VALUES
					('$type', '$subtype', '$class')
			");
			
			// add entry to cache
			$cache_obj->id = $id;
			$this->cache[$id] = $cache_obj;
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
	function remove($type, $subtype) {
		$type = $this->db->sanitizeString($type);
		$subtype = $this->db->sanitizeString($subtype);
	
		$success = $this->db->deleteData("
			DELETE FROM {$this->db->getTablePrefix()}entity_subtypes
			WHERE type = '$type' AND subtype = '$subtype'
		");
		
		if ($success) {
			// invalidate the cache
			$this->cache = null;
		}
		
		return (bool) $success;
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
	function update($type, $subtype, $class = '') {
		$id = $this->getId($type, $subtype);
		if (!$id) {
			return false;
		}
	
		if ($this->cache === null) {
			$this->populateCache();
		}
	
		$unescaped_class = $class;
	
		$type = $this->db->sanitizeString($type);
		$subtype = $this->db->sanitizeString($subtype);
		$class = $this->db->sanitizeString($class);
		
		$success = $this->db->updateData("
			UPDATE {$this->db->getTablePrefix()}entity_subtypes
			SET type = '$type', subtype = '$subtype', class = '$class'
			WHERE id = $id
		");
	
		if ($success && isset($this->cache[$id])) {
			$this->cache[$id]->class = $unescaped_class;
		}
	
		return $success;
	}
}
