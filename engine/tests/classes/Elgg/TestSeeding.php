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

	public function clearSeeds() {
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
	 * Create an entity for testing with all hooks, events and permissions check disabled
	 *
	 * @param string $types      An array of entity types
	 * @param array  $attributes Entity attributes
	 * @param array  $metadata   Entity metadata
	 *
	 * @return \ElggEntity
	 */
	public function createOne($types = 'object', array $attributes = [], array $metadata = []) {
		$seeds = $this->createMany($types, 1, $attributes, $metadata);

		return array_shift($seeds);
	}

	/**
	 * Create a set of entities for testing with all hooks, events and permissions check disabled
	 *
	 * @param mixed $types An array of entity types
	 * @param int   $limit Number of entities to seed
	 *
	 * @return \ElggEntity[]
	 */
	public function createMany($types = 'object', $limit = 2, array $attributes = [], array $metadata = []) {

		$types = (array) $types;
		$entities = [];

		foreach ($types as $type) {
			$seeded = 0;
			while ($seeded < $limit) {
				$seeded++;
				if (!isset($attributes['subtype'])) {
					$attributes['subtype'] = $this->getRandomSubtype();
				}
				
				switch ($type) {
					case 'object' :
						$entities[] = $this->createObject($attributes, $metadata);
						break;

					case 'user' :
						$entities[] = $this->createUser($attributes, $metadata);
						break;

					case 'group' :
						$entities[] = $this->createGroup($attributes, $metadata);
						break;
				}
			}
		}

		return $entities;
	}
	
	/**
	 * Takes over seeding from the Seeding trait to keep track of the seeded entity
	 *
	 * @param array $attributes User entity attributes
	 * @param array $metadata   User entity metadata
	 * @param array $options    Seeding options
	 *
	 * @return \ElggUser
	 */
	final public function createUser(array $attributes = [], array $metadata = [], array $options = []) {
		$metadata = array_merge([
			'validated' => true, //by default all users in the tests are validated
		], $metadata);
		$entity = $this->createSeededUser($attributes, $metadata, $options);
		
		$this->_seeds[] = $entity;
		
		return $entity;
	}
	
	/**
	 * Takes over seeding from the Seeding trait to keep track of the seeded entity
	 *
	 * @param array $attributes Group entity attributes
	 * @param array $metadata   Group entity metadata
	 * @param array $options    Additional options
	 *
	 * @return \ElggGroup
	 */
	final public function createGroup(array $attributes = [], array $metadata = [], array $options = []) {
		$entity = $this->createSeededGroup($attributes, $metadata, $options);

		$this->_seeds[] = $entity;
		
		return $entity;
	}
	
	/**
	 * Takes over seeding from the Seeding trait to keep track of the seeded entity
	 *
	 * @param array $attributes Object entity attributes
	 * @param array $metadata   Object entity metadata
	 * @param array $options    Additional options
	 *
	 * @return \ElggObject
	 */
	final public function createObject(array $attributes = [], array $metadata = [], array $options = []) {
		$entity = $this->createSeededObject($attributes, $metadata, $options);
		
		$this->_seeds[] = $entity;
		
		return $entity;
	}
}
