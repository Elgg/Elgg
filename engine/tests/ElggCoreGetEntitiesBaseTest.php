<?php

/**
 * Setup entities for getter tests
 */
abstract class ElggCoreGetEntitiesBaseTest extends \ElggCoreUnitTest {

	/** @var array */
	protected $entities;

	/**
	 * Called before each test object.
	 */
	public function __construct() {

		parent::__construct();

		$ia = elgg_set_ignore_access(true);

		$this->entities = array();

		// create some fun objects to play with.
		// 5 with random subtypes
		$subtypes = $this->getRandomValidSubtypes(['object'], 5);
		foreach ($subtypes as $subtype) {
			$e = new \ElggObject();
			$e->subtype = $subtype;
			$e->save();

			$this->entities[] = $e;
		}

		// and users
		$subtypes = $this->getRandomValidSubtypes(['user'], 5);
		foreach ($subtypes as $subtype) {
			$e = new \ElggUser();
			$e->username = "test_user_" . rand();
			$e->subtype = $subtype;
			$e->save();

			$this->entities[] = $e;
		}

		// and groups
		$subtypes = $this->getRandomValidSubtypes(['group'], 5);
		foreach ($subtypes as $subtype) {
			$e = new \ElggGroup();
			$e->subtype = $subtype;
			$e->save();

			$this->entities[] = $e;
		}

		elgg_set_ignore_access($ia);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		foreach ($this->entities as $e) {
			$e->delete();
		}

		parent::__destruct();
	}

}
