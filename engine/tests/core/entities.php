<?php
/**
 * Elgg Test ElggEntities
 * 
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreEntityTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {

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


	}

	/**
	 * A basic test that will be called and fail.
	 */
	public function testElggEntityConstructor() {
		$this->assertTrue(FALSE);
	}
}

// ElggEntity is an abstract class with no abstact methods.
class ElggEntityTest extends ElggEntity { }
