<?php
/**
 * Elgg Object
 *
 * Elgg objects are the most common means of storing information in the database.
 * They are a child class of ElggEntity, so receive all the benefits of the Entities,
 * but also include a title and description field.
 *
 * An ElggObject represents a row from the objects_entity table, as well
 * as the related row in the entities table as represented by the parent
 * ElggEntity object.
 *
 * @internal Title and description are stored in the objects_entity table.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Object
 */
class ElggObject extends ElggEntity {
	/**
	 * Initialise the attributes array to include the type,
	 * title, and description.
	 */
	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['type'] = "object";
		$this->attributes['title'] = "";
		$this->attributes['description'] = "";
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Load or create a new ElggObject.
	 *
	 * If no arguments are passed, create a new entity.
	 *
	 * If an argument is passed attempt to load a full Object entity.  Arguments
	 * can be:
	 *  - The GUID of an object entity.
	 *  - A DB result object with a guid property
	 *
	 * @param mixed $guid If an int, load that GUID.  If a db row then will attempt to load the rest of the data.
	 * @throws IOException If passed an incorrect guid
	 * @throws InvalidParameterException If passed an Elgg* Entity that isn't an ElggObject
	 */
	function __construct($guid = null) {
		$this->initialise_attributes();

		if (!empty($guid)) {
			// Is $guid is a DB row - either a entity row, or a object table row.
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid->guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid->guid));
				}
			}

			// Is $guid is an ElggObject? Use a copy constructor
			else if ($guid instanceof ElggObject) {
				elgg_deprecated_notice('This type of usage of the ElggObject constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}

			// Is this is an ElggEntity but not an ElggObject = ERROR!
			else if ($guid instanceof ElggEntity) {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggObject'));
			}

			// We assume if we have got this far, $guid is an int
			else if (is_numeric($guid)) {
				if (!$this->load($guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid));
				}
			}

			else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			}
		}
	}

	/**
	 * Loads the full ElggObject when given a guid.
	 *
	 * @param int $guid
	 * @return bool
	 * @throws InvalidClassException
	 */
	protected function load($guid) {
		// Test to see if we have the generic stuff
		if (!parent::load($guid)) {
			return false;
		}

		// Check the type
		if ($this->attributes['type']!='object') {
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
		}

		// Load missing data
		$row = get_object_entity_as_row($guid);
		if (($row) && (!$this->isFullyLoaded())) {
			// If $row isn't a cached copy then increment the counter
			$this->attributes['tables_loaded'] ++;
		}

		// Now put these into the attributes array as core values
		$objarray = (array) $row;
		foreach($objarray as $key => $value) {
			$this->attributes[$key] = $value;
		}

		return true;
	}

	/**
	 * Saves object-specific attributes.
	 *
	 * @internal Object attributes are saved in the objects_entity table.
	 *
	 * @return bool
	 */
	public function save() {
		// Save ElggEntity attributes
		if (!parent::save()) {
			return false;
		}

		// Save ElggObject-specific attributes
		return create_object_entity($this->get('guid'), $this->get('title'), $this->get('description'), $this->get('container_guid'));
	}

	/**
	 * Return sites that this object is a member of
	 *
	 * Site membership is determined by relationships and not site_guid.d
	 *
	 * @param string $subtype Optionally, the subtype of result we want to limit to
	 * @param int $limit The number of results to return
	 * @param int $offset Any indexing offset
	 * @todo This should be moved to ElggEntity
	 * @todo Unimplemented
	 */
	function getSites($subtype="", $limit = 10, $offset = 0) {
		return get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add this object to a site
	 *
	 * @param int $site_guid The guid of the site to add it to
	 * @return bool
	 */
	function addToSite($site_guid) {
		return add_site_object($this->getGUID(), $site_guid);
	}

	/**
	 * Set the container for this object.
	 *
	 * @param int $container_guid The ID of the container.
	 * @return bool
	 */
	function setContainer($container_guid) {
		$container_guid = (int)$container_guid;

		return $this->set('container_guid', $container_guid);
	}

	/**
	 * Returns the container GUID of this object.
	 *
	 * @return int
	 */
	function getContainer() {
		return $this->get('container_guid');
	}

	/**
	 * Returns the contain entity for this object.
	 *
	 * @return mixed ElggGroup object or false.
	 */
	function getContainerEntity() {
		$result = get_entity($this->getContainer());

		if (($result) && ($result instanceof ElggGroup)) {
			return $result;
		}

		return false;
	}

	/**
	 * Get the collections associated with a object.
	 *
	 * @param string $subtype Optionally, the subtype of result we want to limit to
	 * @param int $limit The number of results to return
	 * @param int $offset Any indexing offset
	 * @return unknown
	 */
	//public function getCollections($subtype="", $limit = 10, $offset = 0) { get_object_collections($this->getGUID(), $subtype, $limit, $offset); }

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'title',
			'description',
		));
	}
}