<?php

namespace Elgg\Traits\Entity;

class ElggObjectMetadataIntegrationTest extends MetadataIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createObject();
	}
	
	protected function getUnsavedEntity(): \ElggEntity {
		return new \ElggObject();
	}
}
