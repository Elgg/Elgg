<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\IconsIntegrationTestCase;

class ElggObjectIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createObject();
	}
}
