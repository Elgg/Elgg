<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\MetadataIntegrationTestCase;

class ElggGroupMetadataIntegrationTest extends MetadataIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
	
	protected function getUnsavedEntity(): \ElggEntity {
		return new \ElggGroup();
	}
}
