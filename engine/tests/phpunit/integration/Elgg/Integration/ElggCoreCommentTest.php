<?php

namespace Elgg\Integration;

use Elgg\LegacyIntegrationTestCase;
use ElggComment;
use ElggObject;

/**
 * Elgg Test \ElggComment
 *
 * @group IntegrationTests
 */
class ElggCoreCommentTest extends LegacyIntegrationTestCase {
	/**
	 * @var ElggComment
	 */
	protected $comment;

	/**
	 * @var ElggObject
	 */
	protected $container;

	public function up() {

		$this->commenter = $this->createOne('user');

		$this->container_owner = $this->createOne('user');
		$this->container = $this->createOne('object', [
			'owner_guid' => $this->container_owner->guid,
		]);

		$this->comment = $this->createOne('object', [
			'subtype' => 'comment',
			'container_guid' => $this->container->guid,
			'owner_guid' => $this->commenter->guid,
		]);
	}

	public function down() {
		$this->commenter->delete();
		$this->container_owner->delete();
	}

	public function testCommentAccessSync() {

		$this->assertTrue(_elgg_services()->events->hasHandler('update:after', 'all', '_elgg_comments_access_sync'));

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
