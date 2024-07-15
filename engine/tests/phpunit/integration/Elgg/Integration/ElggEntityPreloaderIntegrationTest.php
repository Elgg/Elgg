<?php

namespace Elgg\Integration;

use Elgg\Cache\EntityCache;
use Elgg\EntityPreloader;

class ElggEntityPreloaderIntegrationTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var EntityPreloader
	 */
	protected $preloader;
	
	/**
	 * @var EntityCache
	 */
	protected $entityCache;

	public function up() {
		$this->preloader = _elgg_services()->entityPreloader;
		$this->entityCache = _elgg_services()->entityCache;
		$this->entityCache->clear();
	}
	
	public function testPreloadWithUnusableParameters() {
		$this->preloader->preload([], []); // skipped empty array
		$this->preloader->preload(['no object 1', 'no_object_2'], []); // skipped no objects
		
		$object1 = $this->createObject();
		$object2 = $this->createObject();
		$this->preloader->preload([$object1, $object2], []); // skipped no properties
		$this->preloader->preload([$object1, $object2], ['foo']); // skipped non existing properties
	}
	
	public function testNotPreloadIfThereIsOnlyOneToLoad() {
		$owner1 = $this->createUser();
		$owner2 = $this->createUser();
		$object1 = $this->createObject(['owner_guid' => $owner1->guid, 'foo' => $owner1->guid]);
		$object2 = $this->createObject(['owner_guid' => $owner2->guid]);
		
		$this->assertNull($this->entityCache->load($owner1->guid));
		$this->assertNull($this->entityCache->load($owner2->guid));
		$this->preloader->preload([$object1, $object2], ['foo']);
		$this->assertNull($this->entityCache->load($owner1->guid));
		$this->assertNull($this->entityCache->load($owner2->guid));
		
		$this->entityCache->save($owner1);
		$this->assertNotNull($this->entityCache->load($owner1->guid));
		
		$this->preloader->preload([$object1, $object2], ['owner_guid']);
		// should not preload as owner1 is already loaded thus there is just one to preload
		$this->assertNull($this->entityCache->load($owner2->guid));
	}
	
	public function testPreloadFromSingleProperty() {
		$owner1 = $this->createUser();
		$owner2 = $this->createUser();
		$object1 = $this->createObject(['owner_guid' => $owner1->guid]);
		$object2 = $this->createObject(['owner_guid' => $owner2->guid]);
		
		$this->assertNull($this->entityCache->load($owner1->guid));
		$this->assertNull($this->entityCache->load($owner2->guid));
		$this->preloader->preload([$object1, $object2], ['owner_guid']);
		$this->assertNotNull($this->entityCache->load($owner1->guid));
		$this->assertNotNull($this->entityCache->load($owner2->guid));
	}
	
	public function testPreloadFromMultipleProperty() {
		$owner1 = $this->createUser();
		$owner2 = $this->createUser();
		$owner3 = $this->createUser();
		$object1 = $this->createObject(['owner_guid' => $owner3->guid, 'foo' => $owner1->guid]);
		$object2 = $this->createObject(['owner_guid' => $owner3->guid, 'bar' => $owner2->guid]);
		
		$this->assertNull($this->entityCache->load($owner1->guid));
		$this->assertNull($this->entityCache->load($owner2->guid));
		$this->preloader->preload([$object1, $object2], ['foo', 'bar']);
		$this->assertNotNull($this->entityCache->load($owner1->guid));
		$this->assertNotNull($this->entityCache->load($owner2->guid));
	}
}
