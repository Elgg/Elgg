<?php

namespace Elgg;

use Elgg\Traits\Seeding;

trait TestSeeding {

	use Seeding {
		createObject as createSeededObject;
		createGroup as createSeededGroup;
		createUser as createSeededUser;
	}

	/**
	 * @var \ElggEntity
	 */
	private $_seeds = [];

	public function clearSeeds(): void {
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
			foreach ($this->_seeds as $seed) {
				if (!elgg_entity_exists($seed->guid)) {
					// entity probably deleted during tests
					continue;
				}
				
				$seed->delete();
			}
		});
	}

	/**
	 * Create an entity for testing with all events and permissions check disabled
	 *
	 * @param string|array $types      An array of entity types
	 * @param array        $properties Entity attributes/metadata
	 *
	 * @return \ElggEntity
	 */
	public function createOne(string|array $types = 'object', array $properties = []): \ElggEntity {
		$seeds = $this->createMany($types, 1, $properties);

		return array_shift($seeds);
	}

	/**
	 * Create a set of entities for testing with all events and permissions check disabled
	 *
	 * @param string|array $types      An array of entity types
	 * @param int          $limit      Number of entities to seed
	 * @param array        $properties Entity attributes/metadata
	 *
	 * @return \ElggEntity[]
	 */
	public function createMany(string|array $types = 'object', int $limit = 2, array $properties = []): array {

		$types = (array) $types;
		$entities = [];

		foreach ($types as $type) {
			$seeded = 0;
			while ($seeded < $limit) {
				$seeded++;
				if (!isset($properties['subtype'])) {
					$properties['subtype'] = $this->getRandomSubtype();
				}
				
				switch ($type) {
					case 'object' :
						$entities[] = $this->createObject($properties);
						break;

					case 'user' :
						$entities[] = $this->createUser($properties);
						break;

					case 'group' :
						$entities[] = $this->createGroup($properties);
						break;
				}
			}
		}

		return $entities;
	}
	
	/**
	 * Takes over seeding from the Seeding trait to keep track of the seeded entity
	 *
	 * @param array $properties Entity attributes/metadata
	 * @param array $options    Seeding options
	 *
	 * @return \ElggUser
	 */
	final public function createUser(array $properties = [], array $options = []): \ElggUser {
		$properties = array_merge([
			'validated' => true, //by default all users in the tests are validated
		], $properties);
		$entity = $this->createSeededUser($properties, $options);
		
		$this->_seeds[] = $entity;
		
		return $entity;
	}
	
	/**
	 * Takes over seeding from the Seeding trait to keep track of the seeded entity
	 *
	 * @param array $properties Entity attributes/metadata
	 * @param array $options    Additional options
	 *
	 * @return \ElggGroup
	 */
	final public function createGroup(array $properties = [], array $options = []): \ElggGroup {
		$entity = $this->createSeededGroup($properties, $options);

		$this->_seeds[] = $entity;
		
		return $entity;
	}
	
	/**
	 * Takes over seeding from the Seeding trait to keep track of the seeded entity
	 *
	 * @param array $properties Entity attributes/metadata
	 * @param array $options    Additional options
	 *
	 * @return \ElggObject
	 */
	final public function createObject(array $properties = [], array $options = []): \ElggObject {
		$entity = $this->createSeededObject($properties, $options);
		
		$this->_seeds[] = $entity;
		
		return $entity;
	}
}
