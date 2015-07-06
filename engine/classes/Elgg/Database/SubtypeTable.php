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
class SubtypeTable {
	/**
	 * Global Elgg configuration
	 * 
	 * @var \stdClass
	 */
	private $CONFIG;

	private $cache;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
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
	function getSubtype($subtype_id) {
		if (!$subtype_id) {
			return '';
		}

		$cache = $this->getCache();

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
	function retrieveFromCache($type, $subtype) {
		foreach ($this->getCache() as $obj) {
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
	 * @return string Empty string if not set
	 * @see get_subtype_from_id()
	 * @see get_subtype_class_from_id()
	 * @access private
	 */
	function getClass($type, $subtype) {
		$obj = $this->retrieveFromCache($type, $subtype);

		return !empty($obj->class) ? $obj->class : '';
	}
	
	/**
	 * Returns the class name for a subtype id.
	 *
	 * @param int $subtype_id The subtype id
	 *
	 * @return string Empty string if not set
	 * @see get_subtype_from_id()
	 * @access private
	 */
	function getClassFromId($subtype_id) {
		$cache = $this->getCache();

		return !empty($cache[$subtype_id]->class) ? $cache[$subtype_id]->class : "";
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

		$cache_obj = $this->retrieveFromCache($type, $subtype);
		if (!$cache_obj) {
			// In cache we store non-SQL-escaped strings because that's what's returned by query
			$cache_obj = (object) array(
				'type' => $type,
				'subtype' => $subtype,
				'class' => $class,
			);

			$type = sanitise_string($type);
			$subtype = sanitise_string($subtype);

			$id = _elgg_services()->db->insertData("
				INSERT INTO {$this->CONFIG->dbprefix}entity_subtypes
				(type, subtype, class) VALUES ('$type', '$subtype', '')
			");

			$cache_obj->id = (int)$id;
			$this->cache[$id] = $cache_obj;
		}

		$cache_obj->class = $class;
		return $cache_obj->id;
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
		$type = sanitise_string($type);
		$subtype = sanitise_string($subtype);
	
		$success = _elgg_services()->db->deleteData("DELETE FROM {$this->CONFIG->dbprefix}entity_subtypes"
			. " WHERE type = '$type' AND subtype = '$subtype'");
		
		if ($success) {
			$this->cache = null;
		}
		
		return (bool) $success;
	}

	/**
	 * Get the array of subtype rows from the DB
	 *
	 * @return array
	 */
	private function getCache() {
		if ($this->cache === null) {
			$results = _elgg_services()->db->getData("
				SELECT `id`, `type`, `subtype` FROM {$this->CONFIG->dbprefix}entity_subtypes
			");

			$this->cache = [];
			foreach ($results as $row) {
				$this->cache[$row->id] = $row;
			}

			if (!$this->cache) {
				$this->add("object", "plugin", "ElggPlugin");
				$this->add("object", "file", "ElggFile");
				$this->add("object", "widget", "ElggWidget");
				$this->add("object", "comment", "ElggComment");
				$this->add("object", "elgg_upgrade", 'ElggUpgrade');
			}
		}
		return $this->cache;
	}
}