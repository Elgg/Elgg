<?php

/**
 * Elgg Test \ElggComment
 *
 * @package    Elgg
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

	public function up() {
		$this->container = new \ElggObject();
		$this->container->subtype = $this->getRandomSubtype();
		$this->container->access_id = ACCESS_PUBLIC;
		$this->container->save();

		$this->comment = new \ElggComment();
		$this->comment->description = 'comment description';
		$this->comment->container_guid = $this->container->guid;
		$this->comment->access_id = $this->container->access_id;
		$this->comment->save();
	}

	public function down() {
		$this->container->delete();
		$this->comment->delete();
	}

	public function testCommentAccessSync() {

		$this->comment->disableCaching();
		$this->container->disableCaching();

		$this->assertEqual($this->comment->access_id, $this->container->access_id);

		// now change the access of the container
		$this->container->access_id = ACCESS_LOGGED_IN;
		$this->container->save();

		$updated_container = get_entity($this->container->guid);
		$updated_comment = get_entity($this->comment->guid);

		$this->assertEqual($updated_comment->access_id, $updated_container->access_id);
	}

}
