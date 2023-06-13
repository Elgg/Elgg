<?php

namespace Elgg\TheWire;

class ElggWireUnitTest extends \Elgg\UnitTestCase {
	
	public function testCantComment() {
		$this->startPlugin();
		
		$wire = $this->createObject([
			'subtype' => 'thewire',
		]);
		
		$this->assertInstanceOf(\ElggWire::class, $wire);
		$this->assertFalse($wire->canComment());
		
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);
		
		$this->assertFalse($wire->canComment());
	}
}
