<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\IconsIntegrationTestCase;

class ElggGroupIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
