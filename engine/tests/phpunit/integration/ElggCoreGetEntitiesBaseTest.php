<?php

/**
 * Setup entities for getter tests
 */
abstract class ElggCoreGetEntitiesBaseTest extends \Elgg\LegacyIntegrationTestCase {

	/** @var array */
	protected $entities;

	/**
	 * Called before each test object.
	 */
	public function setUp() {

		parent::setUp();

		$ia = elgg_set_ignore_access(true);

		$this->entities = array();

		// create some fun objects to play with.
		// 5 with random subtypes
		$subtypes = $this->getRandomValidSubtypes(['object'], 5);
		foreach ($subtypes as $subtype) {
			$e = $this->createObject([
				'subtype' => $subtype,
			]);

			$this->entities[] = $e;
		}

		// and users
		$subtypes = $this->getRandomValidSubtypes(['user'], 5);
		foreach ($subtypes as $subtype) {
			$e = $this->createUser([
				'subtype' => $subtype,
			]);
			$this->entities[] = $e;
		}

		// and groups
		$subtypes = $this->getRandomValidSubtypes(['group'], 5);
		foreach ($subtypes as $subtype) {
			$e = $this->createGroup([
				'subtype' => $subtype,
			]);

			$this->entities[] = $e;
		}

		elgg_set_ignore_access($ia);
	}

	/**
	 * Called after each test object.
	 */
	public function tearDown() {
		foreach ($this->entities as $e) {
			$e->delete();
		}

		parent::tearDown();
	}


}
