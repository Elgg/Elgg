<?php

namespace Elgg\Messages;

use Elgg\Plugins\PluginTesting;

/**
 * @group Plugins
 */
class ElggMessageUnitTest extends \Elgg\UnitTestCase {
	
	use PluginTesting;
	
	public function up() {
		
	}
	
	public function down() {
		
	}
	
	public function testCantComment() {
		$this->startPlugin();
		
		$message = $this->createObject([
			'subtype' => 'messages',
		]);
		
		$this->assertInstanceOf(\ElggMessage::class, $message);
		$this->assertFalse($message->canComment());
		
		$user = $this->createUser();
		$session = _elgg_services()->session;
		
		$session->setLoggedInUser($user);
		
		$this->assertFalse($message->canComment());
		
		$session->removeLoggedInUser();
	}
}
