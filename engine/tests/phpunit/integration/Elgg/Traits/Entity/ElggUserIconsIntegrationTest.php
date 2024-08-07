<?php

namespace Elgg\Traits\Entity;

class ElggUserIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
}
