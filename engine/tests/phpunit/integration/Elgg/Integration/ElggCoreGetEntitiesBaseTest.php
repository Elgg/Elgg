<?php

namespace Elgg\Integration;

use Elgg\Application;
use Elgg\Database\Seeder;
use Elgg\LegacyIntegrationTestCase;
use Elgg\TestSeeder;
use ElggGroup;
use ElggObject;
use ElggUser;

/**
 * Setup entities for getter tests
 */
abstract class ElggCoreGetEntitiesBaseTest extends LegacyIntegrationTestCase {

	/**
	 * @var \ElggEntity[]
	 */
	static private $_entities;

	/**
	 * @var string[]
	 */
	static private $_types;

	/**
	 * @var string[]
	 */
	static private $_subtypes;

	/**
	 * @var \ElggEntity[]
	 */
	protected $entities;

	/**
	 * @var string[]
	 */
	protected $types;

	/**
	 * @var string[]
	 */
	protected $subtypes;

	/**
	 * @var bool
	 */
	protected $ignore_access;

	public function up() {
		$this->entities = self::$_entities;
		$this->types = self::$_types;
		$this->subtypes = self::$_subtypes;
	}

	public function down() {

	}

	/**
	 *
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		$app = Application::$_instance;
		if (!$app || !$app->getDbConnection()) {
			return;
		}

		$seeder = new TestSeeder();

		$ia = elgg_set_ignore_access();

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
			$subtype = 'test_object_subtype_' . rand();
			$e = $seeder->createObject([
				'subtype' => $subtype,
			]);

			self::$_entities[] = $e;
			self::$_subtypes['object'][] = $subtype;
		}

		// and users
		for ($i = 0; $i < 5; $i++) {
			$subtype = "test_user_subtype_" . rand();
			$e = $seeder->createUser([
				'subtype' => $subtype,
			]);

			self::$_entities[] = $e;
			self::$_subtypes['user'][] = $subtype;
		}

		// and groups
		for ($i = 0; $i < 5; $i++) {
			$subtype = "test_group_subtype_" . rand();
			$e = $seeder->createGroup([
				'subtype' => $subtype,
			]);

			self::$_entities[] = $e;
			self::$_subtypes['group'][] = $subtype;
		}

		elgg_set_ignore_access($ia);
	}

	public static function tearDownAfterClass() {

		$app = Application::$_instance;
		if ($app && $app->getDbConnection()) {

			$ia = elgg_set_ignore_access();

			foreach (self::$_entities as $e) {
				$e->delete();
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

		parent::tearDownAfterClass();
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
		$r = [];

		for ($i = 1; $i <= $num; $i++) {
			do {
				$t = $this->types[array_rand($this->types)];
			} while (in_array($t, $r) && count($r) < count($this->types));

			$r[] = $t;
		}

		shuffle($r);

		return $r;
	}

	/**
	 * Get a random valid subtype (that we just created)
	 *
	 * @param array $types Type of objects to return valid subtypes for.
	 * @param int   $num   of subtypes.
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
