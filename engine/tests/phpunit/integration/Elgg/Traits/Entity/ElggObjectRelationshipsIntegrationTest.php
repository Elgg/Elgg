<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\RelationshipsIntegrationTestCase;

class ElggObjectRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createObject();
	}
}
