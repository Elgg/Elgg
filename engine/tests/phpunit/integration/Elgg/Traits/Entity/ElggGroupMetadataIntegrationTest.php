<?php

namespace Elgg\Traits\Entity;

class ElggGroupMetadataIntegrationTest extends MetadataIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
	
	protected function getUnsavedEntity(): \ElggEntity {
		return new \ElggGroup();
	}
}
