<?php

namespace Elgg\Traits\Entity;

class ElggObjectRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createObject();
	}
}
