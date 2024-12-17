<?php

namespace Elgg\Traits\Entity;

/**
 * Bundle all metadata related functions for an \ElggEntity
 *
 * @since 6.1
 */
trait Metadata {
	
	/**
	 * Holds metadata until entity is saved.  Once the entity is saved,
	 * metadata are written immediately to the database.
	 *
	 * @var array
	 */
	protected array $temp_metadata = [];
	
	/**
	 * Return the value of a piece of metadata.
	 *
	 * @param string $name Name
	 *
	 * @return mixed The value, or null if not found.
	 */
	public function getMetadata(string $name): mixed {
		$metadata = $this->getAllMetadata();
		return elgg_extract($name, $metadata);
	}
	
	/**
	 * Get all entity metadata
	 *
	 * @return array
	 */
	public function getAllMetadata(): array {
		if (!$this->guid) {
			return array_map(function($values) {
				return count($values) > 1 ? $values : $values[0];
			}, $this->temp_metadata);
		}
		
		$metadata = _elgg_services()->metadataCache->load($this->guid);
		if ($metadata === null) {
			$metadata = elgg_extract($this->guid, _elgg_services()->metadataCache->populateFromEntities($this->guid));
		}
		
		if (empty($metadata)) {
			return [];
		}

		$metadata_values = [];
		foreach ($metadata as $md) {
			$metadata_values[$md->name][] = $md->value;
		}

		return array_map(function($values) {
			return count($values) > 1 ? $values : $values[0];
		}, $metadata_values);
	}
	
	/**
	 * Set metadata on this entity.
	 *
	 * Plugin developers usually want to use the magic set method ($entity->name = 'value').
	 * Use this method if you want to explicitly set the owner or access of the metadata.
	 * You cannot set the owner/access before the entity has been saved.
	 *
	 * @param string $name       Name of the metadata
	 * @param mixed  $value      Value of the metadata (doesn't support assoc arrays)
	 * @param string $value_type 'text', 'integer', or '' for automatic detection
	 * @param bool   $multiple   Allow multiple values for a single name.
	 *                           Does not support associative arrays.
	 *
	 * @return bool
	 */
	public function setMetadata(string $name, mixed $value, string $value_type = '', bool $multiple = false): bool {
		if ($value === null || $value === '' || $value === []) {
			return $this->deleteMetadata($name);
		}
		
		// normalize value to an array that we will loop over
		// remove indexes if value already an array.
		if (is_array($value)) {
			$value = array_values(array_filter($value, function($var) {
				// strip null and '' values from array
				return !is_null($var) && $var !== '';
			}));
		} else {
			$value = [$value];
		}
		
		if (count($value) === 0) {
			return $this->deleteMetadata($name);
		}
		
		if (empty($this->guid)) {
			// unsaved entity. store in temp array
			return $this->setTempMetadata($name, $value, $multiple);
		}
		
		// saved entity. persist md to db.
		// disable metadatacache to always check the database to prevent racing conditions
		$md_cache = _elgg_services()->metadataCache;
		$md_cache_enabled = $md_cache->isEnabled();
		$md_cache->disable();
		$restore_md_cache = function() use ($md_cache, $md_cache_enabled) {
			if ($md_cache_enabled) {
				$md_cache->enable();
			}
		};
		
		if (!$multiple) {
			// using getIDsByName to prevent populating the metadata cache
			$existing_ids = _elgg_services()->metadataTable->getIDsByName($this->guid, $name);
			
			if ((is_array($existing_ids) || count($value) > 1) && isset($existing_ids)) {
				// remove current metadata if needed
				if (!$this->deleteMetadata($name)) {
					$restore_md_cache();
					
					return false;
				}
			}
		}
		
		if (count($value) > 1) {
			// new value is a multiple valued metadata
			$multiple = true;
		}
		
		// create new metadata
		foreach ($value as $value_tmp) {
			$metadata = new \ElggMetadata();
			$metadata->entity_guid = $this->guid;
			$metadata->name = $name;
			$metadata->value = $value_tmp;
			
			if (!empty($value_type)) {
				$metadata->value_type = $value_type;
			}
			
			if (_elgg_services()->metadataTable->create($metadata, $multiple) === false) {
				$restore_md_cache();
				
				return false;
			}
		}
		
		$restore_md_cache();
		
		return true;
	}
	
	/**
	 * Set temp metadata on this entity.
	 *
	 * @param string $name     Name of the metadata
	 * @param mixed  $value    Value of the metadata (doesn't support assoc arrays)
	 * @param bool   $multiple Allow multiple values for a single name.
	 *                         Does not support associative arrays.
	 *
	 * @return bool
	 */
	protected function setTempMetadata(string $name, mixed $value, bool $multiple = false): bool {
		// if overwrite, delete first
		if (!$multiple) {
			unset($this->temp_metadata[$name]);
			if (count($value)) {
				// only save if value array contains data
				$this->temp_metadata[$name] = $value;
			}
			
			return true;
		}
		
		if (!isset($this->temp_metadata[$name])) {
			$this->temp_metadata[$name] = [];
		}
		
		$this->temp_metadata[$name] = array_merge($this->temp_metadata[$name], $value);
		
		return true;
	}
	
	/**
	 * Deletes all metadata on this object (metadata.entity_guid = $this->guid).
	 * If you pass a name, only metadata matching that name will be deleted.
	 *
	 * @warning Calling this with no $name will clear all metadata on the entity.
	 *
	 * @param null|string $name The name of the metadata to remove.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function deleteMetadata(?string $name = null): bool {
		if (!$this->guid) {
			// remove from temp_metadata
			if (isset($name)) {
				if (isset($this->temp_metadata[$name])) {
					unset($this->temp_metadata[$name]);
				}
			} else {
				$this->temp_metadata = [];
			}
			
			return true;
		}
		
		return elgg_delete_metadata([
			'guid' => $this->guid,
			'limit' => false,
			'metadata_name' => $name,
		]);
	}
}
