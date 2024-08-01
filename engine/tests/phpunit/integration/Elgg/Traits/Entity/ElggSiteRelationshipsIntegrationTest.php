<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\RelationshipsIntegrationTestCase;

class ElggSiteRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createSite();
	}
	
	public function testRelationshipOnEntityDelete() {
		$this->markTestSkipped("The \ElggSite can't be deleted");
	}
	
	public function testPreventRelationshipOnEntityDelete() {
		$this->markTestSkipped("The \ElggSite can't be deleted");
	}
	
	public function testEntityMethodRemoveAllRelationships() {
		$this->markTestSkipped('This would remove too many relationships (eg. active plugins)');
	}
}
