<?php

use Elgg\IntegrationTestCase;

class ElggCommentIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggEntity[]
	 */
	protected $entities = [];
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		// cleanup created entities
		foreach ($this->entities as $entity) {
			$entity->delete();
		}
	}
	
	public function testCommentOwnerCanEditContentOwnerCant() {
		$this->entities[] = $owner = $this->createUser();
		$this->entities[] = $content_owner = $this->createUser();
		$this->entities[] = $content = $this->createObject([
			'owner_guid' => $content_owner->guid,
		]);
		$this->entities[] = $comment = $this->createObject([
			'subtype' => 'comment',
			'owner_guid' => $owner->guid,
			'container_guid' => $content->guid,
		]);
		
		$this->assertInstanceOf(ElggComment::class, $comment);
		$this->assertTrue($comment->canEdit($owner->guid));
		$this->assertFalse($comment->canEdit($content_owner->guid));
	}
	
	public function testCommentOwnerCanEditContentOwnerCantGroupOwnerCanEdit() {
		$this->entities[] = $owner = $this->createUser();
		$this->entities[] = $content_owner = $this->createUser();
		$this->entities[] = $group_owner = $this->createUser();
		$this->entities[] = $group = $this->createGroup([
			'owner_guid' => $group_owner->guid,
		]);
		$this->entities[] = $content = $this->createObject([
			'owner_guid' => $content_owner->guid,
			'container_guid' => $group->guid,
		]);
		$this->entities[] = $comment = $this->createObject([
			'subtype' => 'comment',
			'owner_guid' => $owner->guid,
			'container_guid' => $content->guid,
		]);
		
		$this->assertInstanceOf(ElggComment::class, $comment);
		$this->assertTrue($comment->canEdit($owner->guid));
		$this->assertTrue($comment->canEdit($group_owner->guid));
		$this->assertFalse($comment->canEdit($content_owner->guid));
	}
}
