<?php
/**
 * Elgg Regression Tests -- Trac Bugfixes
 * Any bugfixes from Trac that require testing belong here.
 *
 * @package Elgg
 * @subpackage Test
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
class ElggCoreRegressionBugsTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		$this->ia = elgg_set_ignore_access(TRUE);
		parent::__construct();
		
		// all __construct() code should come after here
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
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		elgg_set_ignore_access($this->ia);
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * #1558
	 */
	public function testElggObjectClearAnnotations() {
		$this->entity = new ElggObject();
		$guid = $this->entity->save();
		
		$this->entity->annotate('test', 'hello', ACCESS_PUBLIC);
		
		$this->entity->clearAnnotations('does not exist');
		
		$num = $this->entity->countAnnotations('test');
		
		//$this->assertIdentical($num, 1);
		$this->assertEqual($num, 1);
		
		// clean up
		$this->entity->delete();
	}
}
