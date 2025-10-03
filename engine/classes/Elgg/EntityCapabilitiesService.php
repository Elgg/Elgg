<?php

namespace Elgg;

/**
 * Entity capabilities service
 *
 * @internal
 * @since 4.1
 */
class EntityCapabilitiesService {
	
	/**
	 * Stores the entities with their capabilities
	 *
	 * @var array
	 */
	protected array $entities = [];
	
	/**
	 * Checks if a capability is enabled for a specified type/subtype
	 *
	 * @param string $type       type of the entity
	 * @param string $subtype    subtype of the entity
	 * @param string $capability name of the capability to check
	 * @param bool   $default    default value to return if it is not explicitly set
	 *
	 * @return bool
	 */
	public function hasCapability(string $type, string $subtype, string $capability, bool $default = false): bool {
		return elgg_extract($capability, elgg_extract($subtype, elgg_extract($type, $this->entities, []), [])) ?? $default;
	}
	
	/**
	 * Sets the capability value for a specified type/subtype
	 *
	 * @param string $type       type of the entity
	 * @param string $subtype    subtype of the entity
	 * @param string $capability name of the capability to set
	 * @param bool   $value      value to set
	 *
	 * @return void
	 */
	public function setCapability(string $type, string $subtype, string $capability, bool $value): void {
		if (!isset($this->entities[$type])) {
			$this->entities[$type] = [];
		}

		if (!isset($this->entities[$type][$subtype])) {
			$this->entities[$type][$subtype] = [];
		}
		
		$this->entities[$type][$subtype][$capability] = $value;
	}
	
	/**
	 * Returns an array of type/subtypes with the requested capability enabled
	 *
	 * @param string $capability name of the capability to set
	 *
	 * @return array
	 */
	public function getTypesWithCapability(string $capability): array {
		$result = [];
		
		foreach ($this->entities as $type => $subtypes) {
			foreach ($subtypes as $subtype => $capabilities) {
				if (elgg_extract($capability, $capabilities) !== true) {
					continue;
				}
				
				if (!isset($result[$type])) {
					$result[$type] = [];
				}
				
				$result[$type][] = $subtype;
			}
		}
		
		return $result;
	}
}
