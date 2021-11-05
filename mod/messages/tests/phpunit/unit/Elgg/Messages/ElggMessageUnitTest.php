<?php

namespace Elgg\Messages;

use Elgg\Plugins\PluginTesting;

/**
 * @group Plugins
 */
class ElggMessageUnitTest extends \Elgg\UnitTestCase {
	
	use PluginTesting;

	public function testCantComment() {
		$this->startPlugin();
		
		$message = $this->createObject([
			'subtype' => 'messages',
		]);
		
		$this->assertInstanceOf(\ElggMessage::class, $message);
		$this->assertFalse($message->canComment());
		
		$user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		$this->assertFalse($message->canComment());
	}
}
