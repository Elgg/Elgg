<?php

namespace Elgg\SystemLog;

use Elgg\Exceptions\RuntimeException;

/**
 * Represents a system log entry
 *
 * @property int    $id
 * @property int    $object_id
 * @property string $object_class
 * @property string $object_type
 * @property string $object_subtype
 * @property string $event
 * @property int    $performed_by_guid
 * @property int    $owner_guid
 * @property int    $access_id
 * @property string $enabled
 * @property int    $time_created
 * @property string $ip_address
 */
class SystemLogEntry {

	protected const INTEGER_ATTR_NAMES = [
		'id',
		'object_id',
		'performed_by_guid',
		'owner_guid',
		'access_id',
		'time_created',
	];
	
	protected array $attributes = [];
	
	/**
	 * Constructor
	 *
	 * @param \stdClass $row DB row
	 */
	public function __construct(\stdClass $row) {
		$this->initializeAttributes();
		
		foreach ($row as $key => $value) {
			if (!array_key_exists($key, $this->attributes)) {
				continue;
			}
			
			if (in_array($key, static::INTEGER_ATTR_NAMES)) {
				$value = (int) $value;
			}
			
			$this->attributes[$key] = $value;
		}
	}
	
	/**
	 * Initialize the attributes array
	 *
	 * @return void
	 */
	protected function initializeAttributes(): void {
		$this->attributes['id'] = null;
		$this->attributes['object_id'] = null;
		$this->attributes['object_class'] = null;
		$this->attributes['object_type'] = null;
		$this->attributes['object_subtype'] = null;
		$this->attributes['event'] = null;
		$this->attributes['performed_by_guid'] = null;
		$this->attributes['owner_guid'] = null;
		$this->attributes['access_id'] = null;
		$this->attributes['enabled'] = null;
		$this->attributes['time_created'] = null;
		$this->attributes['ip_address'] = null;
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Elgg\Exceptions\RuntimeException
	 */
	public function __set(string $name, $value) {
		if (!array_key_exists($name, $this->attributes)) {
			throw new RuntimeException("It's not allowed to set {$name} on " . get_class($this));
		}
		
		if (in_array($name, static::INTEGER_ATTR_NAMES)) {
			$value = (int) $value;
		}
		
		$this->attributes[$name] = $value;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function __get($name) {
		return $this->attributes[$name] ?? null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __isset($name) : bool {
		return isset($this->attributes[$name]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __unset($name): void {
		if (!array_key_exists($name, $this->attributes)) {
			return;
		}
		
		$this->attributes[$name] = null;
	}

	/**
	 * Get entry object
	 *
	 * @return \ElggData|false
	 */
	public function getObject() {
		$class = $this->object_class;
		$id = $this->object_id;

		if (!class_exists($class)) {
			// failed autoload
			return false;
		}

		$getters = [
			\ElggAnnotation::class => 'elgg_get_annotation_from_id',
			\ElggMetadata::class => 'elgg_get_metadata_from_id',
			\ElggRelationship::class => 'elgg_get_relationship',
		];

		if (isset($getters[$class]) && is_callable($getters[$class])) {
			$object = call_user_func($getters[$class], $id);
		} elseif (is_subclass_of($class, \ElggEntity::class)) {
			$object = get_entity($id);
		} else {
			// surround with try/catch because object could be disabled
			try {
				// assuming the object is a custom entity class
				return get_entity($id);
			} catch (\Exception $e) {
				return false;
			} catch (\Error $e) {
				elgg_log("SystemLogEntry is unable to construct '{$class}' with ID: '{$this->object_id}': {$e->getMessage()}", 'ERROR');
				return false;
			}
		}

		if (!is_object($object) || get_class($object) !== $class) {
			return false;
		}

		return $object;
	}
}
