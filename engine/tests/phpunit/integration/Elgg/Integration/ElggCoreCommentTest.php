<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggComment
 *
 * @group IntegrationTests
 */
class ElggCoreCommentTest extends IntegrationTestCase {
	/**
	 * @var \ElggComment
	 */
	protected $comment;

	/**
	 * @var \ElggObject
	 */
	protected $container;

	public function up() {

		$this->commenter = $this->createUser();
		elgg()->session->setLoggedInUser($this->commenter);
		
		$this->container_owner = $this->createUser();
		$this->container = $this->createObject([
			'owner_guid' => $this->container_owner->guid,
		]);

		$this->comment = $this->createObject([
			'subtype' => 'comment',
			'container_guid' => $this->container->guid,
			'owner_guid' => $this->commenter->guid,
		]);
	}

	public function testCommentAccessSync() {

		$this->assertTrue(_elgg_services()->events->hasHandler('update:after', 'all', \Elgg\Comments\SyncContainerAccessHandler::class));

		$this->comment->disableCaching();
		$this->container->disableCaching();

		$this->assertEquals($this->container->access_id, $this->comment->access_id);

		// now change the access of the container
		$this->container->access_id = 123456;
		
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->assertTrue($this->container->save());
			
			$updated_container = get_entity($this->container->guid);
			$updated_comment = get_entity($this->comment->guid);
	
			$this->assertEquals(123456, $updated_container->access_id);
			$this->assertEquals(123456, $updated_comment->access_id);
		});
	}
}
