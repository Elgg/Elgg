<?php

/**
 * Loads ElggEntity attributes from DB or validates those passed in via constructor
 *
 * @access private
 */
class ElggAttributeLoader {

	/**
	 * @var array names of attributes in all entities
	 */
	protected static $primary_attr_names = array(
		'guid',
		'type',
		'subtype',
		'owner_guid',
		'container_guid',
		'site_guid',
		'access_id',
		'time_created',
		'time_updated',
		'last_action',
		'enabled'
	);

	/**
	 * @var array names of secondary attributes required for the entity
	 */
	protected $secondary_attr_names = array();

	/**
	 * @var string entity type (not class) required for fetched primaries
	 */
	protected $required_type;

	/**
	 * @var array
	 */
	protected $initialized_attributes;

	/**
	 * @var string class of object being loaded
	 */
	protected $class;

	/**
	 * @var bool should access control be considered when fetching entity?
	 */
	public $requires_access_control = true;

	/**
	 * @var callable function used to load attributes from {prefix}entities table
	 */
	public $primary_loader = 'get_entity_as_row';

	/**
	 * @var callable function used to load attributes from secondary table
	 */
	public $secondary_loader = '';

	/**
	 * @var callable function used to load all necessary attributes
	 */
	public $full_loader = '';

	/**
	 * @param string $class class of object being loaded
	 * @param string $required_type entity type this is being used to populate
	 * @param array $initialized_attrs attributes after initializeAttributes() has been run
	 * @throws InvalidArgumentException
	 */
	public function __construct($class, $required_type, array $initialized_attrs) {
		if (!is_string($class)) {
			throw new InvalidArgumentException('$class must be a class name.');
		}
		$this->class = $class;

		if (!is_string($required_type)) {
			throw new InvalidArgumentException('$requiredType must be a system entity type.');
		}
		$this->required_type = $required_type;

		$this->initialized_attributes = $initialized_attrs;
		unset($initialized_attrs['tables_split'], $initialized_attrs['tables_loaded']);
		$all_attr_names = array_keys($initialized_attrs);
		$this->secondary_attr_names = array_diff($all_attr_names, self::$primary_attr_names);
	}

	protected function isMissingPrimaries($row) {
		return array_diff(self::$primary_attr_names, array_keys($row)) !== array();
	}

	protected function isMissingSecondaries($row) {
		return array_diff($this->secondary_attr_names, array_keys($row)) !== array();
	}

	protected function checkType($row) {
		if ($row['type'] !== $this->required_type) {
			$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($row['guid'], $this->class));
			throw new InvalidClassException($msg);
		}
	}

	/**
	 * Get all required attributes for the entity, validating any that are passed in. Returns empty array
	 * if can't be loaded (Check $failure_reason).
	 *
	 * This function splits loading between "primary" attributes (those in {prefix}entities table) and
	 * "secondary" attributes (e.g. those in {prefix}objects_entity), but can load all at once if a
	 * combined loader is available.
	 *
	 * @param mixed $row a row loaded from DB (array or stdClass) or a GUID
	 * @return array will be empty if failed to load all attributes (access control or entity doesn't exist)
	 *
	 * @throws InvalidArgumentException|LogicException|IncompleteEntityException
	 */
	public function getRequiredAttributes($row) {
		if (!is_array($row) && !($row instanceof stdClass)) {
			// assume row is the GUID
			$row = array('guid' => $row);
		}
		$row = (array) $row;
		if (empty($row['guid'])) {
			throw new InvalidArgumentException('$row must be or contain a GUID');
		}

		// these must be present to support isFullyLoaded()
		foreach (array('tables_split', 'tables_loaded') as $key) {
			if (isset($this->initialized_attributes[$key])) {
				$row[$key] = $this->initialized_attributes[$key];
			}
		}

		$was_missing_primaries = $this->isMissingPrimaries($row);
		$was_missing_secondaries = $this->isMissingSecondaries($row);

		// some types have a function to load all attributes at once, it should be faster
		if (($was_missing_primaries || $was_missing_secondaries) && is_callable($this->full_loader)) {
			$fetched = (array) call_user_func($this->full_loader, $row['guid']);
			if (!$fetched) {
				return array();
			}
			$row = array_merge($row, $fetched);
			$this->checkType($row);
		} else {
			if ($was_missing_primaries) {
				if (!is_callable($this->primary_loader)) {
					throw new LogicException('Primary attribute loader must be callable');
				}
				if ($this->requires_access_control) {
					$fetched = (array) call_user_func($this->primary_loader, $row['guid']);
				} else {
					$ignoring_access = elgg_set_ignore_access();
					$fetched = (array) call_user_func($this->primary_loader, $row['guid']);
					elgg_set_ignore_access($ignoring_access);
				}
				if (!$fetched) {
					return array();
				}
				$row = array_merge($row, $fetched);
			}

			// We must test type before trying to load the secondaries so that InvalidClassException
			// gets thrown. Otherwise the secondary loader will fail and return false.
			$this->checkType($row);

			if ($was_missing_secondaries) {
				if (!is_callable($this->secondary_loader)) {
					throw new LogicException('Secondary attribute loader must be callable');
				}
				$fetched = (array) call_user_func($this->secondary_loader, $row['guid']);
				if (!$fetched) {
					if ($row['type'] === 'site') {
						// A special case is needed for sites: When vanilla ElggEntities are created and
						// saved, these are stored w/ type "site", but with no sites_entity row. These
						// are probably only created in the unit tests.
						// @todo Don't save vanilla ElggEntities with type "site"
						$row['guid'] = (int) $row['guid'];
						return $row;
					}
					throw new IncompleteEntityException("Secondary loader failed to return row for {$row['guid']}");
				}
				$row = array_merge($row, $fetched);
			}
		}

		// loading complete: re-check missing and check type
		if (($was_missing_primaries && $this->isMissingPrimaries($row))
				|| ($was_missing_secondaries && $this->isMissingSecondaries($row))) {
			throw new LogicException('Attribute loaders failed to return proper attributes');
		}

		// guid needs to be an int  http://trac.elgg.org/ticket/4111
		$row['guid'] = (int) $row['guid'];

		return $row;
	}
}
