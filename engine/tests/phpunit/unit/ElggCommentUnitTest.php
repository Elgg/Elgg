<?php
class ElggCommentUnitTest extends \Elgg\UnitTestCase {

	public function testCommentInitialize() {
		$comment = new \ElggComment();
		
		$this->assertEquals(1, $comment->level);
	}
	
	public function testCantComment() {
		
		$comment = $this->createObject([
			'subtype' => 'comment',
		]);
		
		$this->assertInstanceOf(ElggComment::class, $comment);
		$this->assertFalse($comment->canComment());
		
		$user = $this->createUser();
		
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$this->assertFalse($comment->canComment());
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
	
	public function testGetLevel() {
		$comment = new \ElggComment();
		
		$this->assertEquals(1, $comment->getLevel());
		
		$comment->level = 3;
		$this->assertEquals(3, $comment->getLevel());
		
		unset($comment->level);
		$this->assertEquals(1, $comment->getLevel());
	}
	
	public function testThreadEntity() {
		$comment = $this->createObject([
			'subtype' => 'comment',
		]);
		
		$comment2 = $this->createObject([
			'subtype' => 'comment',
		]);
		
		// no thread points to self
		$this->assertEquals($comment->guid, $comment->getThreadGUID());
		
		$comment->thread_guid = $comment2->guid;
		
		$this->assertEquals($comment2->guid, $comment->getThreadGUID());
		
		$thread_entity = $comment->getThreadEntity();
		$this->assertInstanceOf(ElggComment::class, $thread_entity);
		$this->assertEquals($comment2->guid, $thread_entity->guid);
		
		$comment->thread_guid = -1;
		$this->assertNull($comment->getThreadEntity());
	}
}
