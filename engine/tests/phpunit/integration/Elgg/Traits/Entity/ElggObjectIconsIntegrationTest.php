<?php

namespace Elgg\Traits\Entity;

class ElggObjectIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createObject();
	}
}
