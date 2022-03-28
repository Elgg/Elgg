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
	
	public function testGetRowWithoutGUID() {
		$this->assertFalse(_elgg_services()->entityTable->getRow(null));
	}
	
	public function testGetRowWithNonExistingGUID() {
		$this->assertFalse(_elgg_services()->entityTable->getRow(-1));
	}
	
	public function testGetRowWithExistingGUID() {
		$object = $this->createObject();
		
		$result = _elgg_services()->entityTable->getRow($object->guid);
		$this->assertNotFalse($result);
		$this->assertNotEmpty($result); // should be a \stdClass with data
	}
}
