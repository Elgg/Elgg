<?php

/**
 * @group UnitTests
 * @group ElggData
 */
class ElggCommentUnitTest extends \Elgg\UnitTestCase {
	
	public function up() {
		
	}
	
	public function down() {
		
	}
	
	public function testCantComment() {
		
		$comment = $this->createObject([
			'subtype' => 'comment',
		]);
		
		$this->assertInstanceOf(ElggComment::class, $comment);
		$this->assertFalse($comment->canComment());
		
		$user = $this->createUser();
		$session = _elgg_services()->session;
		
		$session->setLoggedInUser($user);
		
		$this->assertFalse($comment->canComment());
		
		$session->removeLoggedInUser();
	}
	
	public function testIsCreatedByContentOwner() {
		$user_1 = $this->createUser();
		$user_2 = $this->createUser();
		
		$content = $this->createObject([
			'owner_guid' => $user_1->guid,
		]);
		
		/* @var $owner_comment \ElggComment */
		$owner_comment = $this->createObject([
			'subtype' => 'comment',
			'container_guid' => $content->guid,
			'owner_guid' => $user_1->guid,
		]);
		
		$this->assertTrue($owner_comment->isCreatedByContentOwner());
		
		/* @var $other_comment \ElggComment */
		$other_comment = $this->createObject([
			'subtype' => 'comment',
			'container_guid' => $content->guid,
			'owner_guid' => $user_2->guid,
		]);
		
		$this->assertFalse($other_comment->isCreatedByContentOwner());
	}
}
