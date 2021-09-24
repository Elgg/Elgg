<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class AccessCollectionsIntegrationTest extends IntegrationTestCase {

	/**
	 * @var AccessCollections
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->accessCollections;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}

	public function testAccessPublicNotInWriteAccessArrayWhenInWalledGarden() {
		$user = $this->createUser();
		$config = _elgg_services()->config;
		
		// ensure walled garden is disabled
		$config->walled_garden = false;
		$write_access = $this->service->getWriteAccessArray($user->guid, true);
		
		$this->assertIsArray($write_access);
		$this->assertArrayHasKey(ACCESS_PUBLIC, $write_access);
		
		// enable walled garden
		$config->walled_garden = true;
		$write_access = $this->service->getWriteAccessArray($user->guid, true);
		
		$this->assertIsArray($write_access);
		$this->assertArrayNotHasKey(ACCESS_PUBLIC, $write_access);
	}
}
