<?php
namespace Elgg;

/**
 * Loads \ElggEntity attributes from DB or validates those passed in via constructor
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 */
class AttributeLoader {

	/**
	 * @var array names of attributes in all entities
	 *
	 * @todo require this to be injected and get it from \ElggEntity
	 */
	protected static $primary_attr_names = [
		'guid',
		'type',
		'subtype',
		'owner_guid',
		'container_guid',
		'access_id',
		'time_created',
		'time_updated',
		'last_action',
		'enabled',
	];

	/**
	 * @var array names of attributes in all entities that should be stored as integer values
	 */
	protected static $integer_attr_names = [
		'guid',
		'owner_guid',
		'container_guid',
		'access_id',
		'time_created',
		'time_updated',
		'last_action',
	];

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
	 * @var callable function used to load all necessary attributes
	 */
	public $full_loader = '';

	/**
	 * @var array retrieved values that are not attributes
	 */
	protected $additional_select_values = [];

	/**
	 * Constructor
	 *
	 * @param string $class             class of object being loaded
	 * @param string $required_type     entity type this is being used to populate
	 * @param array  $initialized_attrs attributes after initializeAttributes() has been run
	 * @throws \InvalidArgumentException
	 */
	public function __construct($class, $required_type, array $initialized_attrs) {
		if (!is_string($class)) {
			throw new \InvalidArgumentException('$class must be a class name.');
		}
		$this->class = $class;

		if (!is_string($required_type)) {
			throw new \InvalidArgumentException('$requiredType must be a system entity type.');
		}
		$this->required_type = $required_type;

		$this->initialized_attributes = $initialized_attrs;
	}

	/**
	 * Get primary attributes missing that are missing
	 *
	 * @param \stdClass $row Database row
	 * @return array
	 */
	protected function isMissingPrimaries($row) {
		return array_diff(self::$primary_attr_names, array_keys($row)) !== [];
	}

	/**
	 * Check that the type is correct
	 *
	 * @param \stdClass $row Database row
	 * @return void
	 * @throws \InvalidClassException
	 */
	protected function checkType($row) {
		if ($row['type'] !== $this->required_type) {
			$msg = "GUID:" . $row['guid'] . " is not a valid " . $this->class;
			throw new \InvalidClassException($msg);
		}
	}

	/**
	 * Get values selected from the database that are not attributes
	 *
	 * @return array
	 */
	public function getAdditionalSelectValues() {
		return $this->additional_select_values;
	}
	
	/**
	 * Get all required attributes for the entity, validating any that are passed in. Returns empty array
	 * if can't be loaded (Check $failure_reason).
	 *
	 * This function splits loads "primary" attributes (those in {prefix}entities table),
	 * but can load all at once if a combined loader is available.
	 *
	 * @param mixed $row a row loaded from DB (array or \stdClass)
	 * @return array will be empty if failed to load all attributes (access control or entity doesn't exist)
	 *
	 * @throws \InvalidArgumentException|\LogicException|\IncompleteEntityException
	 */
	public function getRequiredAttributes(\stdClass $row) {
		$row = (array) $row;
		if (empty($row['guid'])) {
			throw new \InvalidArgumentException('$row must be or contain a GUID');
		}

		$was_missing_primaries = $this->isMissingPrimaries($row);

		// some types have a function to load all attributes at once, it should be faster
		if (($was_missing_primaries) && is_callable($this->full_loader)) {
			$fetched = (array) call_user_func($this->full_loader, $row['guid']);
			if (!$fetched) {
				return [];
			}
			$row = array_merge($row, $fetched);
			$this->checkType($row);
		} else {
			if ($was_missing_primaries) {
				if (!is_callable($this->primary_loader)) {
					throw new \LogicException('Primary attribute loader must be callable');
				}
				if ($this->requires_access_control) {
					$fetched = (array) call_user_func($this->primary_loader, $row['guid']);
				} else {
					$ignoring_access = elgg_set_ignore_access();
					$fetched = (array) call_user_func($this->primary_loader, $row['guid']);
					elgg_set_ignore_access($ignoring_access);
				}
				if (!$fetched) {
					return [];
				}
				$row = array_merge($row, $fetched);
			}
		}

		$row = $this->filterAddedColumns($row);

		$row['subtype'] = (int) $row['subtype'];

		// guid needs to be an int  https://github.com/elgg/elgg/issues/4111
		foreach (self::$integer_attr_names as $key) {
			if (isset($row[$key])) {
				$row[$key] = (int) $row[$key];
			}
		}
		return $row;
	}

	/**
	 * Filter non-attribute keys into $this->additional_select_values
	 *
	 * @param array $row All columns from the query
	 * @return array Columns acceptable for the entity's attributes
	 */
	protected function filterAddedColumns($row) {
		// make an array with keys as acceptable attribute names
		$acceptable_attrs = self::$primary_attr_names;
		$acceptable_attrs = array_combine($acceptable_attrs, $acceptable_attrs);

		foreach ($row as $key => $val) {
			if (!isset($acceptable_attrs[$key])) {
				$this->additional_select_values[$key] = $val;
				unset($row[$key]);
			}
		}
		return $row;
	}
}
