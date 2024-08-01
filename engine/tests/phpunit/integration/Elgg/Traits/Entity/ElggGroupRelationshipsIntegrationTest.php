<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\RelationshipsIntegrationTestCase;

class ElggGroupRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
