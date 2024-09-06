<?php

namespace Elgg\Traits\Entity;

class ElggGroupRelationshipsIntegrationTest extends RelationshipsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
