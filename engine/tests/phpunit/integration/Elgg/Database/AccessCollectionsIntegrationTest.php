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
	
	public function testInvertedCreateUpdate() {
		$user = $this->createUser();
		$acl = new \ElggAccessCollection();
		$acl->owner_guid = $user->guid;
		$acl->name = 'foo';
		$acl->subtype = 'bar';
		
		$this->assertTrue($this->service->update($acl));
		$this->assertNotEmpty($acl->id);
		
		/* @var $loaded \ElggAccessCollection */
		$loaded = $this->service->get($acl->id);
		$this->assertInstanceOf(\ElggAccessCollection::class, $loaded);
		$this->assertEquals($acl->owner_guid, $loaded->owner_guid);
		$this->assertEquals($acl->name, $loaded->name);
		$this->assertEquals($acl->subtype, $loaded->subtype);
		
		$acl->subtype = 'foo';
		$acl->name = 'bar';
		
		$this->assertTrue($this->service->create($acl));
		
		/* @var $loaded \ElggAccessCollection */
		$loaded = $this->service->get($acl->id);
		$this->assertInstanceOf(\ElggAccessCollection::class, $loaded);
		$this->assertEquals($acl->owner_guid, $loaded->owner_guid);
		$this->assertEquals($acl->name, $loaded->name);
		$this->assertEquals($acl->subtype, $loaded->subtype);
	}
}
