<?php

namespace Elgg\Comments;

use Elgg\IntegrationTestCase;

class CountPreloaderIntegrationTest extends IntegrationTestCase {

	public function testPersistentInstance() {
		$object = $this->createObject(['subtype' => 'commentable']);
		
		$service = \Elgg\Comments\DataService::instance();
		$service->setCommentsCount($object->guid, 5678);
		
		$this->assertEquals(5678, $service->getCommentsCount($object));
		
		$other_service = \Elgg\Comments\DataService::instance();
		$this->assertEquals(5678, $other_service->getCommentsCount($object));
	}
	
	public function testListEntitiesPreloadsData() {
		$guids = [
			$this->createObject(['subtype' => 'commentable'])->guid,
			$this->createObject(['subtype' => 'commentable'])->guid,
			$this->createObject(['subtype' => 'commentable'])->guid,
			$this->createObject(['subtype' => 'commentable'])->guid,
			$this->createObject(['subtype' => 'commentable'])->guid,
		];

		// comments
		$this->createObject(['subtype' => 'comment', 'container_guid' => $guids[1]]);
		$this->createObject(['subtype' => 'comment', 'container_guid' => $guids[1]]);
		$this->createObject(['subtype' => 'comment', 'container_guid' => $guids[3]]);
		$this->createObject(['subtype' => 'comment', 'container_guid' => $guids[3]]);
		$this->createObject(['subtype' => 'comment', 'container_guid' => $guids[3]]);
		
		elgg_list_entities(['guids' => $guids]);
		
		$service = \Elgg\Comments\DataService::instance();
		
		$counts = $this->getInaccessableProperty($service, 'counts');
		
		$this->assertEquals(0, $counts[$guids[0]]);
		$this->assertEquals(2, $counts[$guids[1]]);
		$this->assertEquals(0, $counts[$guids[2]]);
		$this->assertEquals(3, $counts[$guids[3]]);
		$this->assertEquals(0, $counts[$guids[4]]);
	}
}
