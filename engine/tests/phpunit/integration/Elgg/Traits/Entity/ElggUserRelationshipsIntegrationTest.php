<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\RelationshipsIntegrationTestCase;

class ElggUserRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
}
