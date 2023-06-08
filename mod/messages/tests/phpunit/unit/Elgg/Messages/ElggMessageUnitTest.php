<?php

namespace Elgg\Messages;

class ElggMessageUnitTest extends \Elgg\UnitTestCase {
	
	public function testCantComment() {
		$this->startPlugin();
		
		$message = $this->createObject([
			'subtype' => 'messages',
		]);
		
		$this->assertInstanceOf(\ElggMessage::class, $message);
		$this->assertFalse($message->canComment());
		
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$this->assertFalse($message->canComment());
	}
}
