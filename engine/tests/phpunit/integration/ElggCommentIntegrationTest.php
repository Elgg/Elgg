<?php

use Elgg\IntegrationTestCase;

class ElggCommentIntegrationTest extends IntegrationTestCase {
	
	public function testCommentOwnerCanEditContentOwnerCant() {
		$owner = $this->createUser();
		$content_owner = $this->createUser();
		$content = $this->createObject([
			'subtype' => 'commentable',
			'owner_guid' => $content_owner->guid,
		]);
		$comment = $this->createObject([
			'subtype' => 'comment',
			'owner_guid' => $owner->guid,
			'container_guid' => $content->guid,
		]);
		
		$this->assertInstanceOf(ElggComment::class, $comment);
		$this->assertTrue($comment->canEdit($owner->guid));
		$this->assertFalse($comment->canEdit($content_owner->guid));
	}
	
	public function testCommentOwnerCanEditContentOwnerCantGroupOwnerCanEdit() {
		$owner = $this->createUser();
		$content_owner = $this->createUser();
		$group_owner = $this->createUser();
		$group = $this->createGroup([
			'owner_guid' => $group_owner->guid,
		]);
		$content = $this->createObject([
			'subtype' => 'commentable',
			'owner_guid' => $content_owner->guid,
			'container_guid' => $group->guid,
		]);
		$comment = $this->createObject([
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
