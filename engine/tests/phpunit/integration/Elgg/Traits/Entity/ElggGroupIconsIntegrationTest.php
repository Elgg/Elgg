<?php

namespace Elgg\Traits\Entity;

class ElggGroupIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
