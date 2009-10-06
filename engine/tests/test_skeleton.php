<?php
/**
 * Elgg Test Skeleton
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreSkeletonTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		// first, hook into ElggCoreUnitTest::__construct()
		$this->__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {

	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// hook into ElggCoreUnitTest::__destruct();
		$this->__destruct();
	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testElggEntityConstructor() {
		$this->assertTrue(FALSE);
	}
}
