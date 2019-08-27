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
}
