<?php
/**
 * Elgg Test \ElggComment
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreCommentTest extends \ElggCoreUnitTest {
	/**
	 * @var \ElggComment
	 */
	protected $comment;
	
	/**
	 * @var \ElggObject
	 */
	protected $container;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->container = new \ElggObject();
		$this->container->access_id = ACCESS_PUBLIC;
		$this->container->save();
		
		$this->comment = new \ElggComment();
		$this->comment->description = 'comment description';
		$this->comment->container_guid = $this->container->guid;
		$this->comment->access_id = $this->container->access_id;
		$this->comment->save();
	}

	public function testCommentAccessSync() {
		
		_elgg_disable_caching_for_entity($this->comment->guid);
		_elgg_disable_caching_for_entity($this->container->guid);
		
		$this->assertEqual($this->comment->access_id, $this->container->access_id);
		
		// now change the access of the container
		$this->container->access_id = ACCESS_LOGGED_IN;
		$this->container->save();
		
		$updated_container = get_entity($this->container->guid);
		$updated_comment = get_entity($this->comment->guid);
		
		$this->assertEqual($updated_comment->access_id, $updated_container->access_id);
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->container->delete();
		$this->comment->delete();
	}
}
