<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\IconsIntegrationTestCase;

class ElggUserIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
}
