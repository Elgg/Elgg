<?php
/**
 *
 */

namespace Elgg;

use Elgg\Database\Seeds\Seeding;

trait TestSeeding {

	use Seeding;

	static $_entities;
	static $_types;
	static $_subtypes;

	protected $entities;
	protected $types;
	protected $subtypes;

	/**
	 * Populates the database with seed entities once
	 * @return void
	 */
	public function seedTestEntities() {

		elgg_set_config('profile_fields', []);
		elgg_set_config('group', []);

		$ia = elgg_set_ignore_access();

		if (empty(self::$_entities)) {

			self::$_entities = [];
			self::$_subtypes = [
				'object' => [],
				'user' => [],
				'group' => [],
				//'site'	=> array()
			];

			// sites are a bit wonky.  Don't use them just now.
			self::$_types = [
				'object',
				'user',
				'group'
			];

			// create some fun objects to play with.
			// 5 with random subtypes
			for ($i = 0; $i < 5; $i++) {
				$subtype = "test_object_subtype_tsp_$i";
				$entities = elgg_get_entities([
					'types' => 'object',
					'subtypes' => $subtype,
					'limit' => 0,
					'batch' => true,
				]);
				foreach ($entities as $entity) {
					$entity->delete();
				}
				$e = $this->createObject([
					'subtype' => $subtype,
				]);

				self::$_entities[] = $e;
				self::$_subtypes['object'][] = $subtype;
			}

			// and users
			for ($i = 0; $i < 5; $i++) {
				$subtype = "test_user_subtype_tsp_$i";
				$entities = elgg_get_entities([
					'types' => 'user',
					'subtypes' => $subtype,
					'limit' => 0,
					'batch' => true,
				]);
				foreach ($entities as $entity) {
					$entity->delete();
				}
				$e = $this->createUser([
					'subtype' => $subtype,
				]);

				self::$_entities[] = $e;
				self::$_subtypes['user'][] = $subtype;
			}

			// and groups
			for ($i = 0; $i < 5; $i++) {
				$subtype = "test_group_subtype_tsp_$i";
				$entities = elgg_get_entities([
					'types' => 'group',
					'subtypes' => $subtype,
					'limit' => 0,
					'batch' => true,
				]);
				foreach ($entities as $entity) {
					$entity->delete();
				}
				$e = $this->createGroup([
					'subtype' => $subtype,
				]);

				self::$_entities[] = $e;
				self::$_subtypes['group'][] = $subtype;
			}
		}

		$this->entities = self::$_entities;
		$this->types = self::$_types;
		$this->subtypes = self::$_subtypes;

		elgg_set_ignore_access($ia);
	}

	/**
	 * Deletes seed entities
	 * @return void
	 */
	public function unseedTestEntities() {
		$ia = elgg_set_ignore_access();
		foreach (self::$_entities as $entity) {
			$this->delete($entity);
		}
		// manually remove subtype entries since there is no way
		// to using the API.
		foreach (self::$_subtypes as $type => $subtypes) {
			foreach ($subtypes as $subtype) {
				remove_subtype($type, $subtype);
			}
		}
		elgg_set_ignore_access($ia);
	}

	/*************************************************
	 * Helpers for getting random types and subtypes *
	 *************************************************/

	/**
	 * Get a random valid subtype
	 *
	 * @param int $num
	 *
	 * @return array
	 */
	protected function getRandomValidTypes($num = 1) {
		$types = $this->types;
		shuffle($types);

		return array_slice($types, 0, $num);
	}

	/**
	 * Get a random valid subtype for an entity type
	 *
	 * @param string $type Entity type
	 *
	 * @return string
	 */
	protected function getRandomValidSubtype($type = 'object') {
		$subtypes = $this->getRandomValidSubtypes([$type], 1);

		return array_shift($subtypes);
	}

	/**
	 * Get a random valid subtype (that we just created)
	 *
	 * @param array $type Type of objects to return valid subtypes for.
	 * @param int   $num  of subtypes.
	 *
	 * @return array
	 */
	protected function getRandomValidSubtypes(array $types, $num = 1) {
		$r = [];
		for ($i = 1; $i <= $num; $i++) {
			do {
				// make sure at least one subtype of each type is returned.
				if ($i - 1 < count($types)) {
					$type = $types[$i - 1];
				} else {
					$type = $types[array_rand($types)];
				}
				$k = array_rand($this->subtypes[$type]);
				$t = $this->subtypes[$type][$k];
			} while (in_array($t, $r));
			$r[] = $t;
		}
		shuffle($r);

		return $r;
	}

	/**
	 * Return an array of invalid strings for type or subtypes.
	 *
	 * @param int $num
	 *
	 * @return string[]
	 */
	protected function getRandomInvalids($num = 1) {
		$r = [];
		for ($i = 1; $i <= $num; $i++) {
			$r[] = 'random_invalid_' . rand();
		}

		return $r;
	}

	/**
	 * Get a mix of valid and invalid types
	 *
	 * @param int $num
	 *
	 * @return array
	 */
	protected function getRandomMixedTypes($num = 2) {
		$have_valid = $have_invalid = false;
		$r = [];
		// need at least one of each type.
		$valid_n = rand(1, $num - 1);
		$r = array_merge($r, $this->getRandomValidTypes($valid_n));
		$r = array_merge($r, $this->getRandomInvalids($num - $valid_n));
		shuffle($r);

		return $r;
	}

	/**
	 * Get random mix of valid and invalid subtypes for types given.
	 *
	 * @param array $types
	 * @param int   $num
	 *
	 * @return array
	 */
	protected function getRandomMixedSubtypes(array $types, $num = 2) {
		$types_c = count($types);
		$r = [];
		// this can be more efficient but I'm very sleepy...
		// want at least one of valid and invalid of each type sent.
		for ($i = 0; $i < $types_c && $num > 0; $i++) {
			// make sure we have a valid and invalid for each type
			if (true) {
				$type = $types[$i];
				$r = array_merge($r, $this->getRandomValidSubtypes([$type], 1));
				$r = array_merge($r, $this->getRandomInvalids(1));
				$num -= 2;
			}
		}
		if ($num > 0) {
			$valid_n = rand(1, $num);
			$r = array_merge($r, $this->getRandomValidSubtypes($types, $valid_n));
			$r = array_merge($r, $this->getRandomInvalids($num - $valid_n));
		}

		//shuffle($r);
		return $r;
	}
}