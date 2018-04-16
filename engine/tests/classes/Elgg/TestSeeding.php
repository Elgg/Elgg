<?php
/**
 *
 */

namespace Elgg;

use Elgg\Database\Seeds\Seeding;
use ElggEntity;

trait TestSeeding {

	use Seeding;

	/**
	 * @var ElggEntity
	 */
	private $_seeds = [];

	public function clearSeeds() {

		_elgg_services()->hooks->backup();
		_elgg_services()->events->backup();

		foreach ($this->_seeds as $seed) {
			$seed->delete();
		}

		_elgg_services()->hooks->restore();
		_elgg_services()->events->restore();
	}

	/**
	 * Create an entity for testing with all hooks, events and permissions check disabled
	 *
	 * @param string $types      An array of entity types
	 * @param array  $attributes Entity attributes
	 * @param array  $metadata   Entity metadata
	 *
	 * @return ElggEntity
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
	 * @return ElggEntity[]
	 */
	public function createMany($types = 'object', $limit = 2, array $attributes = [], array $metadata = []) {

		_elgg_services()->hooks->backup();
		_elgg_services()->events->backup();

		$types = (array) $types;

		$seeds = [];

		foreach ($types as $type) {
			$seeded = 0;
			while ($seeded < $limit) {
				$seeded++;
				if (!isset($attributes['subtype'])) {
					$attributes['subtype'] = $this->getRandomSubtype();
				}
				switch ($type) {
					case 'object' :
						$seeds[] = $this->createObject($attributes, $metadata);
						break;

					case 'user' :
						$seeds[] = $this->createUser($attributes, $metadata);
						break;

					case 'group' :
						$seeds[] = $this->createGroup($attributes, $metadata);
						break;
				}

			}
		}

		$this->_seeds = array_merge($this->_seeds, $seeds);

		_elgg_services()->hooks->restore();
		_elgg_services()->events->restore();

		return $seeds;
	}
}
