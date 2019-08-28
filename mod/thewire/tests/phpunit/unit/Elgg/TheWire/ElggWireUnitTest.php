<?php

namespace Elgg\TheWire;

use Elgg\Plugins\PluginTesting;

/**
 * @group Plugins
 */
class ElggWireUnitTest extends \Elgg\UnitTestCase {
	
	use PluginTesting;
	
	public function up() {
		
	}
	
	public function down() {
		
	}
	
	public function testCantComment() {
		$this->startPlugin();
		
		$wire = $this->createObject([
			'subtype' => 'thewire',
		]);
		
		$this->assertInstanceOf(\ElggWire::class, $wire);
		$this->assertFalse($wire->canComment());
		
		$user = $this->createUser();
		$session = _elgg_services()->session;
		
		$session->setLoggedInUser($user);
		
		$this->assertFalse($wire->canComment());
		
		$session->removeLoggedInUser();
	}
}
