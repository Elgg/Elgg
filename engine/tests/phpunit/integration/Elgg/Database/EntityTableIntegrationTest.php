<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class EntityTableIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var EntityTable
	 */
	protected $service;
	
	public function up() {
		$this->service = _elgg_services()->entityTable;
	}
	
	public function testGetRowWithNonExistingGUID() {
		$this->assertNull(_elgg_services()->entityTable->getRow(-1));
	}
	
	public function testGetRowWithExistingGUID() {
		$object = $this->createObject();
		
		$result = _elgg_services()->entityTable->getRow($object->guid);
		$this->assertNotEmpty($result); // should be a \stdClass with data
	}
}
