<?php

namespace Elgg\Traits\Entity;

class ElggUserRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
}
