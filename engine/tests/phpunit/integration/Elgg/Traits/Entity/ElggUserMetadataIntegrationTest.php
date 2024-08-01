<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\MetadataIntegrationTestCase;

class ElggUserMetadataIntegrationTest extends MetadataIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
	
	protected function getUnsavedEntity(): \ElggEntity {
		return new \ElggUser();
	}
}
